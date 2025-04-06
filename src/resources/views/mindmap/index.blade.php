<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>マインドマップ</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="stylesheet" href="https://unpkg.com/vis-network/styles/vis-network.css" />
  <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
      font-family: 'Comic Sans MS', 'Comic Sans', cursive;
      background: linear-gradient(135deg, #FDEFF9, #E0F7FA);
    }
    #mindmap {
      width: 100vw;
      height: 100vh;
      border: none;
      position: absolute;
    }
    #mapTitle {
      position: absolute;
      top: 20px;
      left: 20px;
      background: rgba(255, 255, 255, 0.9);
      color: #FF69B4;
      font-size: 22px;
      font-weight: bold;
      padding: 8px 16px;
      border-radius: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      z-index: 9999;
      transition: transform 0.3s;
    }
    #mapTitle:hover {
      transform: scale(1.05);
    }
    .addButton {
      position: absolute;
      z-index: 10;
      background: #FFB6C1;
      color: white;
      border: none;
      border-radius: 50%;
      width: 28px;
      height: 28px;
      cursor: pointer;
      font-size: 18px;
      text-align: center;
      line-height: 28px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      display: none;
      transition: background 0.3s, transform 0.2s;
    }
    .addButton:hover {
      background: #FF69B4;
      transform: scale(1.1);
    }
    #inlineEditor {
      position: absolute;
      z-index: 20;
      border: 2px solid #FFB6C1;
      background: #fff0f5;
      font-size: 18px;
      padding: 4px 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      border-radius: 8px;
      display: none;
      outline: none;
    }
  </style>
</head>
<body>
  @include('components.sidebar')
  <div id="mapTitle" class="ml-64">マインドマップ</div>
  <div id="mindmap"></div>
  <div id="inlineEditor" contenteditable="true"></div>

  <script>
    // --- 保存/読み込み機能追加 ---
    // 保存する関数：現在のノード・エッジ状態とタイムスタンプを localStorage に保存
    function saveMindMap() {
      const nodes = dataSet.nodes.get();
      const edges = dataSet.edges.get();
      const state = { nodes, edges, timestamp: Date.now() };
      localStorage.setItem('mindMapState', JSON.stringify(state));
    }

    // 読み込む関数：保存データが存在し、1日以内なら読み込む
    function loadMindMap() {
      const stateStr = localStorage.getItem('mindMapState');
      if (stateStr) {
        const state = JSON.parse(stateStr);
        // 1日 = 86400000ミリ秒以上経過していたら無効にする
        if (Date.now() - state.timestamp > 86400000) {
          localStorage.removeItem('mindMapState');
          return false;
        }
        dataSet.nodes.clear();
        dataSet.edges.clear();
        dataSet.nodes.add(state.nodes);
        dataSet.edges.add(state.edges);
        // nodeIdCounter を更新（最大ID + 1）
        const maxId = state.nodes.reduce((max, n) => Math.max(max, n.id), 0);
        nodeIdCounter = maxId + 1;
        return true;
      }
      return false;
    }
    // --- 保存/読み込み機能追加ここまで ---

    // ① パステルカラーのパレット（最上位ブランチ用）
    const branchColors = [
      { // ピンク系
        background: '#FFD1DC',
        border: '#FFB6C1',
        highlightBg: '#FF8DAA',
        highlightBorder: '#FF6F91',
        hoverBg: '#FFC0CB',
        hoverBorder: '#FFB6C1'
      },
      { // ライラック系
        background: '#E6DAF3',
        border: '#D1B2E8',
        highlightBg: '#C8A2C8',
        highlightBorder: '#B080B0',
        hoverBg: '#E8D0F0',
        hoverBorder: '#D1B2E8'
      },
      { // ペールブルー系
        background: '#B3E5FC',
        border: '#81D4FA',
        highlightBg: '#4FC3F7',
        highlightBorder: '#29B6F6',
        hoverBg: '#B3E5FC',
        hoverBorder: '#81D4FA'
      },
      { // ミントグリーン系
        background: '#C8E6C9',
        border: '#A5D6A7',
        highlightBg: '#81C784',
        highlightBorder: '#66BB6A',
        hoverBg: '#C8E6C9',
        hoverBorder: '#A5D6A7'
      },
      { // レモン系
        background: '#FFF9C4',
        border: '#FFF59D',
        highlightBg: '#FFF176',
        highlightBorder: '#FFEE58',
        hoverBg: '#FFF9C4',
        hoverBorder: '#FFF59D'
      }
    ];

    // ② ルートノード用のカラー
    const rootColor = {
      background: '#FFFFFF',
      border: '#EEEEEE',
      highlightBg: '#F5F5F5',
      highlightBorder: '#E0E0E0',
      hoverBg: '#FAFAFA',
      hoverBorder: '#EEEEEE'
    };

    // ③ サンプル階層データ（各ノードに level を明示的に設定）
    const mindMapData = {
      name: "中央ノード",
      level: 0,
      children: [
        {
          name: "ブランチ A",
          level: 1,
          children: [
            { name: "リーフ A1", level: 2 },
            { name: "リーフ A2", level: 2 }
          ]
        },
        {
          name: "ブランチ B",
          level: 1,
          children: [
            { name: "リーフ B1", level: 2 },
            { name: "リーフ B2", level: 2 }
          ]
        }
      ]
    };

    // ④ ノード・エッジ生成用の変数
    let nodeIdCounter = 1;
    let nodesArray = [];
    let edgesArray = [];
    let buttons = {};
    let selectedNodeId = null;

    // ⑤ 再帰的にノードとエッジを生成する関数
    // ルート直下（レベル1）の場合は branchIndex を使って branchColors から色を割り当て、
    // それ以降は親の色を継承する
    function traverse(node, parentId = null, branchIndex = null) {
      const currentId = nodeIdCounter++;
      let currentColor;
      if (parentId === null) {
        currentColor = rootColor;
      } else if (branchIndex !== null) {
        currentColor = branchColors[branchIndex % branchColors.length];
      } else {
        const parentNode = nodesArray.find(n => n.id === parentId);
        currentColor = parentNode.color;
      }
      // 中央ノードの場合は高さを30pxに調整
      const isCentral = (node.level === 0);
      let nodeStyle = {
        id: currentId,
        label: node.name,
        shape: 'box',
        level: node.level || 0,
        color: {
          background: currentColor.background,
          border: currentColor.border,
          highlight: {
            background: currentColor.highlightBg,
            border: currentColor.highlightBorder
          },
          hover: {
            background: currentColor.hoverBg,
            border: currentColor.hoverBorder
          }
        },
        // フォント指定を削除（デフォルトフォントを使用）
        font: { color: '#000000', size: 16 },
        borderWidth: isCentral ? 4 : 2,
        borderRadius: 12
      };
      if (isCentral) {
        nodeStyle.widthConstraint = { minimum: 200 };
        nodeStyle.heightConstraint = { minimum: 30 };
      }
      nodesArray.push(nodeStyle);
      
      if (parentId !== null) {
        // エッジの smooth 設定：branchIndex がある場合は左右の曲線を設定、なければデフォルトは curvedCW
        let smoothType = branchIndex !== null ? (branchIndex % 2 === 0 ? 'curvedCW' : 'curvedCCW') : 'curvedCW';
        edgesArray.push({ 
          from: parentId, 
          to: currentId, 
          smooth: { enabled: true, type: smoothType, roundness: 0.3 } 
        });
      }
      if (node.children) {
        if (parentId === null) {
          node.children.forEach((child, index) => {
            traverse(child, currentId, index);
          });
        } else {
          node.children.forEach(child => traverse(child, currentId, null));
        }
      }
    }
    traverse(mindMapData);

    // ⑥ vis.js ネットワーク初期化
    const container = document.getElementById('mindmap');
    const dataSet = {
      nodes: new vis.DataSet(nodesArray),
      edges: new vis.DataSet(edgesArray)
    };
    const options = {
      layout: {
        hierarchical: {
          enabled: true,
          direction: 'LR',
          levelSeparation: 150,
          nodeSpacing: 50,
          treeSpacing: 50,
          parentCentralization: true,
          sortMethod: 'directed'
        }
      },
      physics: { enabled: false },
      interaction: { dragNodes: true, zoomView: true },
      // グローバル設定としてのエッジ（各エッジは個別の smooth 設定で上書き）
      edges: {
        smooth: false,
        width: 2,
        color: {
          color: '#CCCCCC',
          highlight: '#FFB6C1',
          hover: '#FFB6C1'
        }
      }
    };
    const network = new vis.Network(container, dataSet, options);

    // ⑦ インライン編集用フィールド
    const inlineEditor = document.getElementById('inlineEditor');

    // ⑧ 各ノードごとに＋ボタンを生成（初期は非表示）
    nodesArray.forEach(n => {
      const btn = document.createElement("button");
      btn.innerText = "＋";
      btn.classList.add("addButton");
      btn.dataset.nodeId = n.id;
      btn.addEventListener("click", function(e) {
        e.stopPropagation();
        addChildNodeAndHide(parseInt(btn.dataset.nodeId));
      });
      container.appendChild(btn);
      buttons[n.id] = btn;
    });

    // ⑨ ノード選択時：選択されたノードの＋ボタンのみ表示
    network.on("selectNode", function(params) {
      Object.values(buttons).forEach(b => b.style.display = "none");
      if (params.nodes.length === 1) {
        selectedNodeId = params.nodes[0];
        showButtonForNode(selectedNodeId);
      }
    });
    network.on("deselectNode", function() {
      selectedNodeId = null;
      Object.values(buttons).forEach(b => b.style.display = "none");
    });
    network.on("dragEnd", function() {
      if (selectedNodeId) {
        showButtonForNode(selectedNodeId);
      }
    });

    // ⑩ 定期更新で＋ボタンの位置を更新（100msごと）
    setInterval(updateAllButtonPositions, 100);
    function updateAllButtonPositions() {
      Object.keys(buttons).forEach(nodeId => {
        showButtonForNode(nodeId);
      });
    }
    function showButtonForNode(nodeId) {
      const bbox = network.getBoundingBox(nodeId);
      if (!bbox) return;
      const canvasX = bbox.right;
      const canvasY = (bbox.top + bbox.bottom) / 2;
      const domPos = network.canvasToDOM({ x: canvasX, y: canvasY });
      const btn = buttons[nodeId];
      if (!btn) return;
      const btnW = btn.offsetWidth || 24;
      const btnH = btn.offsetHeight || 24;
      const offsetX = -(btnW / 2);
      const offsetY = -(btnH / 2);
      btn.style.left = (domPos.x + offsetX) + "px";
      btn.style.top = (domPos.y + offsetY) + "px";
      if (parseInt(nodeId) === selectedNodeId) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    }

    // ⑪ 子ノード追加（中央ノードから直接追加される場合は全て異なる色に）
    function addChildNodeAndHide(parentId) {
      const parentNode = dataSet.nodes.get(parentId);
      const parentLevel = parentNode.level || 0;
      const label = prompt("追加するノードの名前を入力してください:") || "無題";
      const newId = nodeIdCounter++;
      let newColor;
      if (parentLevel === 0) {
        // 中央ノードの既存の子供の色をチェック
        const childrenEdges = dataSet.edges.get({ filter: edge => edge.from === parentId });
        const usedColors = childrenEdges.map(edge => {
          const child = dataSet.nodes.get(edge.to);
          return child ? child.color.background : null;
        });
        // branchColors から未使用の色を順に選ぶ
        const availableColors = branchColors.filter(c => !usedColors.includes(c.background));
        if (availableColors.length > 0) {
          newColor = availableColors[0];
        } else {
          newColor = branchColors[0];
        }
      } else {
        newColor = parentNode.color;
      }
      dataSet.nodes.add({
        id: newId,
        label: label.trim(),
        shape: 'box',
        level: parentLevel + 1,
        color: {
          background: newColor.background,
          border: newColor.border,
          highlight: {
            background: newColor.highlightBg,
            border: newColor.highlightBorder
          },
          hover: {
            background: newColor.hoverBg,
            border: newColor.hoverBorder
          }
        },
        font: parentNode.font,
        borderWidth: 2,
        borderRadius: 12
      });
      // 親ノードからの既存子ノード数で、曲線の種類を決定
      let childrenCount = dataSet.edges.get({ filter: edge => edge.from === parentId }).length;
      let smoothType = (childrenCount % 2 === 0) ? 'curvedCW' : 'curvedCCW';
      dataSet.edges.add({
        from: parentId,
        to: newId,
        smooth: { enabled: true, type: smoothType, roundness: 0.3 }
      });
      const newBtn = document.createElement("button");
      newBtn.innerText = "＋";
      newBtn.classList.add("addButton");
      newBtn.dataset.nodeId = newId;
      newBtn.addEventListener("click", function(e) {
        e.stopPropagation();
        addChildNodeAndHide(newId);
      });
      container.appendChild(newBtn);
      buttons[newId] = newBtn;
      if (buttons[parentId]) {
        buttons[parentId].style.display = "none";
      }
      selectedNodeId = null;
      reapplyLayout();
    }

    // ⑫ 階層レイアウト再適用
    function reapplyLayout() {
      network.setOptions({
        layout: {
          hierarchical: {
            enabled: true,
            direction: 'LR',
            levelSeparation: 150,
            nodeSpacing: 50,
            treeSpacing: 50,
            parentCentralization: true,
            sortMethod: 'directed'
          }
        }
      });
      network.stabilize();
      updateAllButtonPositions();
      // --- 保存状態を更新 ---
      saveMindMap();
    }

    // ⑬ ダブルクリックでノード内インライン編集
    network.on("doubleClick", function(params) {
      if (params.nodes.length === 1) {
        inlineEditNode(params.nodes[0]);
      }
    });
    function inlineEditNode(nodeId) {
      const node = dataSet.nodes.get(nodeId);
      if (!node) return;
      const bbox = network.getBoundingBox(nodeId);
      if (!bbox) return;
      const topLeft = network.canvasToDOM({ x: bbox.left, y: bbox.top });
      const botRight = network.canvasToDOM({ x: bbox.right, y: bbox.bottom });
      const w = botRight.x - topLeft.x;
      const h = botRight.y - topLeft.y;
      inlineEditor.style.left = topLeft.x + "px";
      inlineEditor.style.top = topLeft.y + "px";
      inlineEditor.style.width = w + "px";
      inlineEditor.style.height = h + "px";
      inlineEditor.innerText = node.label;
      inlineEditor.style.display = "block";
      inlineEditor.focus();
      inlineEditor.onblur = function() {
        finishInlineEdit(nodeId);
      };
      inlineEditor.onkeydown = function(e) {
        if (e.key === "Enter") {
          e.preventDefault();
          finishInlineEdit(nodeId);
        }
      };
    }
    function finishInlineEdit(nodeId) {
      const newLabel = inlineEditor.innerText.trim() || "無題";
      dataSet.nodes.update({ id: nodeId, label: newLabel });
      inlineEditor.style.display = "none";
      // --- 保存状態を更新 ---
      saveMindMap();
    }

    // ⑭ ノード削除機能（Backspace/Deleteキーで選択ノードとその子孫を削除）
    // 中央ノード（レベル0）は削除しないように変更
    document.addEventListener("keydown", function(e) {
      if (document.activeElement === inlineEditor) return;
      if (selectedNodeId && (e.key === "Backspace" || e.key === "Delete")) {
        e.preventDefault();
        removeNodeRecursively(selectedNodeId);
      }
    });
    function removeNodeRecursively(nodeId) {
      // 中央ノード（level 0）の場合は削除しない
      const node = dataSet.nodes.get(nodeId);
      if (node.level === 0) {
        return;
      }
      const childEdges = dataSet.edges.get({ filter: edge => edge.from === nodeId });
      childEdges.forEach(edge => {
        removeNodeRecursively(edge.to);
      });
      const connectedEdges = dataSet.edges.get({
        filter: edge => edge.from === nodeId || edge.to === nodeId
      });
      connectedEdges.forEach(edge => {
        dataSet.edges.remove(edge.id);
      });
      dataSet.nodes.remove(nodeId);
      if (buttons[nodeId]) {
        buttons[nodeId].remove();
        delete buttons[nodeId];
      }
      selectedNodeId = null;
      reapplyLayout();
    }

    // --- 読み込み状態をチェック ---
    // ページ読み込み時に、保存状態が存在し1日以内であれば読み込む
    if (loadMindMap()) {
      network.setData(dataSet);
      reapplyLayout();
    }
  </script>
</body>
</html>
