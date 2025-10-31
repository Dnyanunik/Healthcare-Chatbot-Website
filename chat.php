<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Healthcare AI Chat Assistant</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:700|Poppins:400,500,600" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: linear-gradient(135deg, #007bff, #00c6ff);
      color: #fff;
      font-family: "Poppins", sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      font-weight: 700;
      margin: 20px 0;
    }

    /* Top buttons */
    .top-bar {
      width: 100%;
      max-width: 900px;
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .btn-custom {
      border-radius: 6px;
      font-weight: 600;
      padding: 0.6rem 1.2rem;
      transition: all 0.3s ease;
    }

    .btn-custom:hover {
      transform: scale(1.05);
    }

    /* Chat Container */
    .chat-container {
      background: #ffffff10;
      width: 90%;
      max-width: 800px;
      height: 500px;
      border-radius: 20px;
      overflow: hidden;
      backdrop-filter: blur(8px);
      display: flex;
      flex-direction: column;
      margin-top: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .chat-box {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      display: flex;
      flex-direction: column;
      scroll-behavior: smooth;
    }

    .message {
      padding: 12px 18px;
      border-radius: 20px;
      margin: 8px 0;
      max-width: 75%;
      word-wrap: break-word;
    }

    .user-msg {
      background: #ffffff;
      color: #007bff;
      align-self: flex-end;
      border-bottom-right-radius: 4px;
    }

    .bot-msg {
      background: #007bff;
      color: #ffffff;
      align-self: flex-start;
      border-bottom-left-radius: 4px;
    }

    /* Input area */
    .input-area {
      display: flex;
      background: #fff;
      border-top: 1px solid #ddd;
      border-radius: 0 0 20px 20px;
      overflow: hidden;
    }

    .input-area input {
      flex: 1;
      border: none;
      padding: 15px;
      font-size: 1rem;
      outline: none;
    }

    .input-area button {
      background: #007bff;
      border: none;
      color: #fff;
      padding: 0 25px;
      font-size: 1.2rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .input-area button:hover {
      background: #0056b3;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-thumb {
      background: #007bff;
      border-radius: 3px;
    }

    form {
      display: none;
      background: #ffffff15;
      padding: 2rem;
      border-radius: 15px;
      width: 60%;
      margin-top: 40px;
    }

    .form-control {
      margin-top: 15px;
      border-radius: 10px;
    }

    .alert {
      background: #f44336;
      padding: 15px;
      color: #fff;
      border-radius: 5px;
      margin-top: 15px;
    }
  </style>
</head>

<body>

  <div class="container text-center">
    <?php if(isset($_SESSION['full_name'])): ?>
      <div class="top-bar">
        <a href="index.php?logout='1'" class="btn btn-dark btn-custom">Logout</a>
        <button id="formButton" class="btn btn-light btn-custom">Update Info</button>
      </div>
      <h2>Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong> 👋</h2>
      <p style="opacity:0.9;">Ask me anything about health, wellness, or medicine.</p>
    <?php endif; ?>
  </div>

  <!-- 💬 AI Chatbot -->
  <div class="chat-container">
    <div id="chat-box" class="chat-box">
      <div class="bot-msg message">👋 Hi there! I’m your Healthcare AI Assistant. How can I help you today?</div>
    </div>

    <div class="input-area">
      <input type="text" id="user-input" placeholder="Type your health question..." />
      <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
    </div>
  </div>

  <!-- Update User Info Form -->
  <div class="container">
    <form id="form1" name="form1" action="chat.php" method="post">
      <?php include('errors.php'); ?>
      <div class="form-group input-group">
        <input class="form-control" placeholder="Full name" type="text" name="name"
               value="<?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : ''; ?>">
      </div>

      <div class="form-group input-group">
        <input class="form-control" placeholder="Email address" type="text" name="email1"
               value="<?php echo isset($_SESSION['email_id']) ? htmlspecialchars($_SESSION['email_id']) : ''; ?>" readonly>
      </div>

      <div class="form-group input-group">
        <input class="form-control" placeholder="Mobile Number" type="text" name="phone"
               value="<?php echo isset($_SESSION['mobile_no']) ? htmlspecialchars($_SESSION['mobile_no']) : ''; ?>">
      </div>

      <div class="form-group input-group">
        <input class="form-control" placeholder="Create password" type="password" name="pwd1">
      </div>

      <div class="form-group input-group">
        <input class="form-control" placeholder="Repeat password" type="password" name="pwd2">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-dark btn-block" name="update">Update</button>
      </div>
    </form>
  </div>

  <script>
    // Toggle form visibility
    document.getElementById("formButton").addEventListener("click", function() {
      const form = document.getElementById("form1");
      form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
    });

    // 🧠 Gemini API Integration
    const sendBtn = document.getElementById("send-btn");
    const userInput = document.getElementById("user-input");
    const chatBox = document.getElementById("chat-box");

    // Replace with your Gemini API Key
    const GEMINI_API_KEY = "AIzaSyBPKTYHodkBnnt4JrP62cIna-8u58O5ccw";

    async function sendMessage() {
      const message = userInput.value.trim();
      if (!message) return;

      appendMessage(message, "user-msg");
      userInput.value = "";
      appendMessage("Typing...", "bot-msg");

      try {
        const response = await fetch(
          `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`,
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              contents: [
                {
                  role: "user",
                  parts: [{
                    text: `
                      You are a professional healthcare assistant chatbot.
                      Only answer questions related to healthcare, medicine, diseases, symptoms,
                      treatments, diet, or wellness.
                      Be polite and informative.
                      User question: ${message}
                    `
                  }]
                }
              ]
            }),
          }
        );

        const data = await response.json();
        const botReply =
          data?.candidates?.[0]?.content?.parts?.[0]?.text ||
          "⚕️ Sorry, I couldn’t process that right now.";

        removeTyping();
        appendMessage(botReply, "bot-msg");
      } catch (error) {
        removeTyping();
        appendMessage("⚠️ Error fetching response. Please try again later.", "bot-msg");
        console.error(error);
      }
    }

    sendBtn.addEventListener("click", sendMessage);
    userInput.addEventListener("keypress", e => { if (e.key === "Enter") sendMessage(); });

    function appendMessage(text, className) {
      const msg = document.createElement("div");
      msg.className = "message " + className;
      msg.innerText = text;
      chatBox.appendChild(msg);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function removeTyping() {
      const typingMsg = [...chatBox.getElementsByClassName("bot-msg")].find(msg => msg.innerText === "Typing...");
      if (typingMsg) chatBox.removeChild(typingMsg);
    }
  </script>

</body>
</html>
