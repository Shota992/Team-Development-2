<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ChatGPT-like UI with Backend</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Vue.js & Axios -->
  <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen overflow-hidden">
  @include('components.sidebar')
  <div class="flex h-full ml-64">
    <!-- メインコンテンツ -->
    <div id="app" class="flex-1 flex flex-col">
      <!-- ヘッダー -->
      <header class="bg-white shadow px-6 py-4">
        <h1 class="text-lg font-semibold text-gray-700">お手伝いできることはありますか？</h1>
      </header>

      <!-- プリセットボタン（8つ） -->
      <div class="bg-white px-6 py-3 border-b border-gray-200 flex flex-wrap gap-2 justify-center">
        <button @click="selectPreset('現状の組織改善における主要な課題は何ですか？')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          主要課題の分析
        </button>
        <button @click="selectPreset('施策評価と次のステップ提案をしてください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          施策評価と次のステップ提案
        </button>
        <button @click="selectPreset('従業員のフィードバックを踏まえた新しい施策案を教えてください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          従業員視点の施策案
        </button>
        <button @click="selectPreset('コミュニケーション不足の原因を分析し、具体的な改善策を示してください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          コミュニケーション改善
        </button>
        <button @click="selectPreset('リーダーシップ強化のための具体的な戦略や行動を提案してください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          リーダーシップ強化
        </button>
        <button @click="selectPreset('業務環境の改善に向けた具体的な施策や改善点を検討してください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          業務環境の改善
        </button>
        <button @click="selectPreset('人材育成やキャリア開発に関して、今後取り組むべき施策は何ですか？')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          人材育成の強化
        </button>
        <button @click="selectPreset('従業員エンゲージメント向上のために、どのような取り組みが効果的か提案してください。')"
          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
          従業員エンゲージメント向上
        </button>
      </div>

      <!-- チャット表示領域 -->
      <main class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50 relative">
        <!-- 会話履歴（システムメッセージは非表示） -->
        <div v-for="(msg, index) in conversation.filter(m => m.role !== 'system')" :key="index"
             :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']">
          <div :class="[
            'px-4 py-2 rounded-3xl max-w-xl whitespace-pre-wrap shadow-md',
            msg.role === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'
          ]">
            @{{ msg.content }}
          </div>
        </div>
        <!-- Loading indicator (会話の下部) -->
        <div v-if="loading" class="flex items-center justify-center text-sm text-gray-500 mt-4">
          <svg class="w-5 h-5 mr-2 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
              stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v8z"></path>
          </svg>
          考え中...
        </div>
      </main>

      <!-- チャット入力 -->
      <footer class="px-6 py-4 bg-white border-t border-gray-200 flex items-center">
        <textarea v-model="userMessage" @keyup.enter="sendMessage" rows="1"
          class="flex-1 border border-gray-300 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="質問を入力してください..."></textarea>
        <button @click="sendMessage"
          class="ml-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
          送信
        </button>
      </footer>
    </div>
  </div>

  <script>
    new Vue({
      el: '#app',
      data: {
        userMessage: '',
        loading: false,
        conversation: [
          { role: 'system', content: 'あなたはプロのアシスタントです。' },
          { role: 'assistant', content: 'こんにちは！ご質問をどうぞ。' }
        ]
      },
      methods: {
        selectPreset(presetText) {
          this.userMessage = presetText;
        },
        sendMessage() {
          if (this.userMessage.trim() === '') return;
          const message = this.userMessage;
          this.userMessage = '';
          this.loading = true;
          this.scrollToBottom();

          axios.post('{{ route("chat.ask") }}', {
              conversation: this.conversation,
              userMessage: message
          }, {
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
          })
          .then(response => {
            this.conversation = response.data.conversation;
            this.loading = false;
            this.scrollToBottom();
          })
          .catch(error => {
            console.error('チャットエラー:', error);
            this.loading = false;
          });
        },
        scrollToBottom() {
          this.$nextTick(() => {
            const el = document.querySelector('main');
            el.scrollTop = el.scrollHeight;
          });
        }
      }
    });
  </script>
</body>
</html>
