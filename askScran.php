<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="styles/index.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<title>SCRAN | SCRAN Bot</title>
<link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body class="page-bot">

<!-- navbar -->
<?php include 'includes/navbar.php'; ?>

<!-- Banner -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">SCRAN Bot</h1>
    
        <p class="scran-hero-main-subtitle">
     Got a question or want meal inspiration? Ask our SCRAN Bot.
    </p>
  </div>
</section>

<div class="container d-flex justify-content-center my-5">
  <div class="card chatbot-card">
  <div class="card-body">

    <h5>Ask SCRAN Bot</h5>

    <div id="chatBox" >
      <div><strong>Bot:</strong> Hi! Ask me anything about recipes</div>
    </div>

    <div class="d-flex mt-3">
      <input type="text" id="chatInput" class="form-control me-2" placeholder="Ask a question...">
      <button onclick="sendMessage()" class="btn btn-dark">Send</button>
    </div>

  </div>
</div>
</div>




<!-- footer -->
<?php include 'includes/footer.php'; ?>



<script>

let chatHistory = [];

document.getElementById("chatInput").addEventListener("keydown", function(e) {
  if (e.key === "Enter") {
    e.preventDefault(); // stop page refresh / form submit
    sendMessage();
  }
});

async function sendMessage() {
  const input = document.getElementById("chatInput");
  const chatBox = document.getElementById("chatBox");

  const message = input.value.trim();
  if (!message) return;

  // Show user message
  chatBox.innerHTML += `<div><strong>You:</strong> ${message}</div>`;
  input.value = "";

  chatBox.scrollTop = chatBox.scrollHeight;

  // ✅ Add to history BEFORE sending
  chatHistory.push({ role: "user", content: message });

  try {
    const res = await fetch("chatbot.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ messages: chatHistory })
    });

    const data = await res.json();

    // ✅ Save bot reply
    chatHistory.push({ role: "assistant", content: data.reply });

    // Show bot message
    chatBox.innerHTML += `
      <div class="mb-2">
        <strong>Bot:</strong><br>
        ${data.reply}
      </div>
    `;

    chatBox.scrollTop = chatBox.scrollHeight;

  } catch (err) {
    console.error(err);
    chatBox.innerHTML += `<div class="text-danger">Error contacting AI</div>`;
  }
}
</script>


</body>
</html>