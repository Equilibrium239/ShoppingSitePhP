<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FakeChat - AI Chat</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: -apple-system, sans-serif; background: #212121; color: #fff; height: 100vh; display: flex; flex-direction: column; }
    .header { background: #343541; padding: 12px 20px; border-bottom: 1px solid #565869; font-size: 18px; font-weight: 600; }
    .messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 16px; }
    .msg { max-width: 80%; padding: 12px 16px; border-radius: 8px; line-height: 1.5; white-space: pre-wrap; }
    .user { background: #2f5a8a; align-self: flex-end; }
    .assistant { background: #444654; align-self: flex-start; }
    .input-area { padding: 16px 20px; background: #343541; border-top: 1px solid #565869; display: flex; gap: 10px; }
    .input-area textarea { flex: 1; padding: 10px; border-radius: 6px; border: none; background: #40414f; color: #fff; resize: none; font-size: 14px; }
    .input-area button { padding: 10px 20px; border-radius: 6px; border: none; background: #19c37d; color: #fff; font-weight: 600; cursor: pointer; }
    .input-area button:hover { background: #1a7f5a; }
    .loading { color: #888; font-style: italic; }
    .model-select { margin-left: auto; display: flex; align-items: center; gap: 8px; }
    .model-select select { background: #40414f; color: #fff; border: 1px solid #565869; padding: 4px 8px; border-radius: 4px; }
  </style>
</head>
<body>
  <div class="header">
    FakeChat
    <span class="model-select">
      Model:
      <select id="modelSelect">
        <option value="StefanGpt1.0">StefanGpt1.0</option>
        <option value="GPT-5.5">GPT-5.5</option>
        <option value="claude-3.6-opus">Claude 3.6 Opus</option>
        <option value="llama-3">Llama 3</option>
      </select>
    </span>
  </div>

  <div class="messages" id="messages"></div>

  <div class="input-area">
    <textarea id="input" rows="2" placeholder="Type your message..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();send()}"></textarea>
    <button onclick="send()">Send</button>
  </div>

  <script>
    // ⚠️ NEVER put API keys in client-side code in real life
    const API_KEY = 'ak-fc-b928cddd5bd781b9a271b8cdecc52b13a6bdfc713259490b34532c6b11fe39e1';
    const API_URL = 'https://fakecloud.systementor.se/v1/chat/completions';

    const messages = document.getElementById('messages');
    const input = document.getElementById('input');
    const modelSelect = document.getElementById('modelSelect');

    function addMessage(role, content) {
      const div = document.createElement('div');
      div.className = 'msg ' + role;
      div.textContent = content;
      messages.appendChild(div);
      messages.scrollTop = messages.scrollHeight;
    }

    async function send() {
      const text = input.value.trim();
      if (!text) return;

      addMessage('user', text);
      input.value = '';

      const loading = document.createElement('div');
      loading.className = 'loading';
      loading.textContent = 'Thinking...';
      messages.appendChild(loading);

      try {
        const res = await fetch(API_URL, {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + API_KEY,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            model: modelSelect.value,
            messages: [{ role: 'user', content: text }]
          })
        });

        loading.remove();

        if (!res.ok) {
          const err = await res.text();
          addMessage('assistant', 'Error: ' + err);
          return;
        }

        const data = await res.json();
        addMessage('assistant', data.choices[0].message.content);
      } catch (e) {
        loading.remove();
        addMessage('assistant', 'Network error: ' + e.message);
      }
    }

    addMessage('assistant', 'Hello! I\'m FakeChat. Send me a message to start chatting.');
  </script>
</body>
</html>