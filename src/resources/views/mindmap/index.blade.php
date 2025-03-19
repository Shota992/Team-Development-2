<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>マインドマップ</title>
  <link rel="stylesheet" href="https://unpkg.com/vis-network/styles/vis-network.css" />
  <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
  <style>
        html, body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
    }

    #mindmap {
      width: 100vw;  /* ビューポート全体を使用 */
      height: 100vh;
      border: none;  /* 境界線なし */
      position: absolute;
    
    }

    #mapTitle {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(255, 255, 255, 0.8);
      color: #333;
      font-size: 18px;
      font-weight: bold;
      padding: 6px 12px;
      border-radius: 4px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      z-index: 9999; /* マップより前面に表示 */
    }

    .addButton {
      position: absolute;
      z-index: 10;
      background: #2B7CE9;
      color: white;
      border: none;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      cursor: pointer;
      font-size: 16px;
      text-align: center;
      line-height: 24px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      display: none;
    }
    #inlineEditor {
      position: absolute;
      z-index: 20;
      border: 1px solid #2B7CE9;
      background: #ffffff;
      font-size: 16px;
      padding: 2px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      display: none;
      outline: none;
    }
  </style>
</head>
<body>
  <div id="mapTitle">マインドマップ</div>
  <div id="mindmap"></div>
  <div id="inlineEditor" contenteditable="true"></div>

  <script>
    // ① パステルカラーのパレット（最上位ブランチ用）
    const branchColors = [
      { // ピンク系
        background: '#FFA07A',
        border: '#FF6347',
        highlightBg: '#FF7F50',
        highlightBorder: '#FF4500',
        hoverBg: '#FFDAB9',
        hoverBorder: '#FFA07A'
      },
      { // ライラック系
        background: '#C8A2C8',
        border: '#B080B0',
        highlightBg: '#D8BFD8',
        highlightBorder: '#A080A0',
        hoverBg: '#E6CAE6',
        hoverBorder: '#C8A2C8'
      },
      { // ペールブルー系
        background: '#ADD8E6',
        border: '#87CEEB',
        highlightBg: '#87CEFA',
        highlightBorder: '#4682B4',
        hoverBg: '#B0E0E6',
        hoverBorder: '#87CEEB'
      },
      { // ミントグリーン系
        background: '#98FB98',
        border: '#3CB371',
        highlightBg: '#66CDAA',
        highlightBorder: '#2E8B57',
        hoverBg: '#BDFCC9',
        hoverBorder: '#3CB371'
      },
      { // レモン系
        background: '#FFFACD',
        border: '#FFD700',
        highlightBg: '#FFEFD5',
        highlightBorder: '#FFA500',
        hoverBg: '#FFFFE0',
        hoverBorder: '#FFD700'
      }
    ];

    // ② ルートノード用のカラー
    const rootColor = {
      background: '#ffffff',
      border: '#cccccc',
      highlightBg: '#eeeeee',
      highlightBorder: '#aaaaaa',
      hoverBg: '#f5f5f5',
      hoverBorder: '#cccccc'
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
      nodesArray.push({
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
        font: { color: '#000000', size: 16, face: 'Comic Sans MS' },
        borderWidth: 2,
        borderRadius: 12
      });
      if (parentId !== null) {
        edgesArray.push({ from: parentId, to: currentId });
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
      // エッジを直線に設定（smooth: false）
      edges: {
        smooth: false,
        width: 2,
        color: {
          color: '#999999',
          highlight: '#FF4500',
          hover: '#FF7F50'
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
      dataSet.edges.add({ from: parentId, to: newId });
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
    }

    // ⑭ ノード削除機能（Backspace/Deleteキーで選択ノードとその子孫を削除）
    document.addEventListener("keydown", function(e) {
      if (document.activeElement === inlineEditor) return;
      if (selectedNodeId && (e.key === "Backspace" || e.key === "Delete")) {
        e.preventDefault();
        removeNodeRecursively(selectedNodeId);
      }
    });
    function removeNodeRecursively(nodeId) {
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
  </script>
</body>
</html>
