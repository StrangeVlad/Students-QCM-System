<?php
require_once "../Resource/db.php";

$modules_query = mysqli_query($conn, "SELECT * FROM modules ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EduQuiz Pro - Student Portal</title>
  <link rel="stylesheet" href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="../assets/img/logo.png">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4CAF50;
      --danger: #f72585;
      --warning: #ff9e00;
      --light: #f8f9fa;
      --dark: #212529;
      --bg-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --border-radius: 10px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7ff;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background: var(--bg-gradient);
      padding: 15px 30px;
      color: white;
      box-shadow: var(--shadow);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 24px;
      color: white;
      text-decoration: none;
    }

    .navbar-subtitle {
      font-size: 14px;
      opacity: 0.8;
    }

    .main-container {
      max-width: 800px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 30px;
      margin-bottom: 30px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 20px;
      margin-bottom: 25px;
      border-bottom: 1px solid #eaeaea;
    }

    .page-title {
      font-size: 24px;
      font-weight: 700;
      margin: 0;
      color: #333;
    }

    .page-subtitle {
      font-size: 14px;
      color: #6c757d;
      margin: 5px 0 0 0;
    }

    .module-card {
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 20px;
      margin-bottom: 20px;
      border-left: 4px solid var(--primary);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
    }

    .module-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(67, 97, 238, 0.15);
      border-left: 4px solid var(--secondary);
    }

    .module-card h3 {
      margin-top: 0;
      color: var(--primary);
      font-weight: 600;
      font-size: 18px;
    }

    .module-card p {
      color: #6c757d;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .module-meta {
      display: flex;
      gap: 15px;
      margin-top: 15px;
      font-size: 12px;
      color: #6c757d;
    }

    .module-meta-item {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .quiz-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eaeaea;
    }

    .timer {
      background: var(--bg-gradient);
      color: white;
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .timer i {
      font-size: 16px;
    }

    .progress-container {
      height: 8px;
      background-color: #e9ecef;
      border-radius: 50px;
      margin-bottom: 25px;
      overflow: hidden;
    }

    .progress-bar {
      height: 100%;
      background: var(--bg-gradient);
      width: 0%;
      transition: width 0.5s ease;
    }

    .question-container {
      background-color: white;
      border-radius: var(--border-radius);
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: var(--shadow);
    }

    .question-container h3 {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
    }

    .options {
      margin-top: 20px;
    }

    .option {
      padding: 15px;
      margin-bottom: 12px;
      background-color: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e9ecef;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .option:hover {
      background-color: #e9ecef;
      border-color: #dce1e9;
    }

    .option.selected {
      background: var(--bg-gradient);
      color: white;
      border-color: var(--primary);
      box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
    }

    .navigation {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .btn {
      border-radius: 50px;
      padding: 12px 25px;
      font-weight: 500;
      transition: all 0.3s ease;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
    }

    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-2px);
    }

    .btn-secondary {
      background: #e9ecef;
      border: 1px solid #dce1e9;
      color: #495057;
    }

    .btn-secondary:hover {
      background: #dfe3e8;
    }

    .btn-success {
      background: var(--success);
      border-color: var(--success);
      color: white;
    }

    .btn-success:hover {
      background: #43a047;
      transform: translateY(-2px);
    }

    .btn-danger {
      background: var(--danger);
      border-color: var(--danger);
      color: white;
    }

    .btn-danger:hover {
      background: #e91e63;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(247, 37, 133, 0.2);
    }

    .result-summary {
      background: white;
      border-radius: var(--border-radius);
      padding: 30px;
      margin-bottom: 30px;
      text-align: center;
      box-shadow: var(--shadow);
      border-top: 5px solid var(--primary);
    }

    .score {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 25px 0;
      color: var(--primary);
    }

    .score-circle {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: var(--bg-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 36px;
      font-weight: 700;
      margin: 30px auto;
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .correct {
      color: var(--success);
      font-weight: 600;
    }

    .incorrect {
      color: var(--danger);
      font-weight: 600;
    }

    .feedback-container {
      background: white;
      border-radius: var(--border-radius);
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: var(--shadow);
    }

    .feedback-container h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
      border-bottom: 1px solid #eaeaea;
      padding-bottom: 15px;
    }

    .feedback-question {
      margin-bottom: 25px;
      border-bottom: 1px solid #f5f5f5;
      padding-bottom: 20px;
    }

    .feedback-question:last-child {
      border-bottom: none;
    }

    .feedback-question h4 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 15px;
      color: #333;
    }

    .answer-label {
      font-weight: 500;
      margin-right: 5px;
      font-size: 14px;
    }

    .hidden {
      display: none;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    @media (max-width: 768px) {
      .main-container {
        padding: 0 15px;
      }

      .quiz-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }

      .navigation {
        flex-direction: column;
        gap: 15px;
      }

      .navigation button {
        width: 100%;
        justify-content: center;
      }

      .card {
        padding: 20px;
      }

      .score-circle {
        width: 120px;
        height: 120px;
        font-size: 30px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <div>
        <span class="navbar-brand">EduQuiz Pro</span>
        <div class="navbar-subtitle">Student Portal</div>
      </div>
      <form action="Register/Logout.php" method="post">
        <button class="btn btn-danger" type="submit">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="main-container" id="module-container">
    <div class="card">
      <div class="card-header">
        <div>
          <h1 class="page-title">Available Modules</h1>
          <p class="page-subtitle">Select a module to begin your test</p>
        </div>
      </div>

      <div class="modules-list" id="modules-list">
        <?php while ($module = mysqli_fetch_assoc($modules_query)): ?>
          <div class="module-card" data-module-id="<?php echo $module['module_id']; ?>" data-time="<?php echo $module['time_limit']; ?>">
            <h3><?php echo htmlspecialchars($module['name']); ?></h3>
            <p><?php echo htmlspecialchars($module['description']); ?></p>
            <div class="module-meta">
              <div class="module-meta-item">
                <i class="fas fa-clock"></i>
                <span><?php echo $module['time_limit']; ?> minutes</span>
              </div>
              <div class="module-meta-item">
                <i class="fas fa-award"></i>
                <span>Pass: <?php echo $module['passing_score']; ?>%</span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <div class="main-container hidden" id="quiz-container">
    <div class="card">
      <div class="quiz-header">
        <div>
          <h2 class="page-title">Module: <span id="module-name"></span></h2>
          <p class="page-subtitle">Answer all questions to complete the quiz</p>
        </div>
        <div class="timer">
          <i class="fas fa-clock"></i>
          <span id="time-remaining">00:00</span>
        </div>
      </div>

      <div class="progress-container">
        <div class="progress-bar" id="progress-bar"></div>
      </div>

      <div id="questions-container">
      </div>

      <div class="navigation">
        <button id="prev-btn" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Previous
        </button>
        <button id="next-btn" class="btn btn-primary">
          Next <i class="fas fa-arrow-right"></i>
        </button>
        <button id="submit-quiz" name="submit-quiz" class="btn btn-success hidden">
          <i class="fas fa-check-circle"></i> Submit Quiz
        </button>
      </div>
    </div>
  </div>

  <div class="main-container hidden" id="results-container">
    <div class="card">
      <div class="card-header">
        <div>
          <h1 class="page-title">Quiz Results</h1>
          <p class="page-subtitle">Module: <span id="result-module-name"></span></p>
        </div>
      </div>

      <div class="result-summary">
        <div class="score-circle">
          <span id="score">0</span><small>%</small>
        </div>
        <div id="pass-fail" class="mt-3"></div>
      </div>

      <div class="feedback-container" id="feedback-container">
        <h3><i class="fas fa-clipboard-check"></i> Question Review</h3>
      </div>

      <div class="text-center">
        <button id="back-to-modules" class="btn btn-primary">
          <i class="fas fa-home"></i> Back to Modules
        </button>
      </div>
    </div>
  </div>

  <script>
    let currentModule = null;
    let currentQuestionIndex = 0;
    let userAnswers = [];
    let quizTimer = null;
    let timeLeft = 0;
    let moduleData = {};

    const moduleContainer = document.getElementById("module-container");
    const quizContainer = document.getElementById("quiz-container");
    const resultsContainer = document.getElementById("results-container");

    document.querySelectorAll(".module-card").forEach((moduleElement) => {
      moduleElement.addEventListener("click", function() {
        const moduleId = parseInt(this.getAttribute("data-module-id"));
        startQuiz(moduleId);
      });
    });

    function startQuiz(moduleId) {
      fetch(`check_module_status.php?module_id=${moduleId}`)
        .then(response => response.json())
        .then(data => {
          if (data.already_attempted) {
            alert(data.message);
            return;
          }

          fetch(`get_module_data.php?module_id=${moduleId}`)
            .then(response => response.json())
            .then(data => {
              moduleData = data;
              currentModule = data.module;
              currentQuestionIndex = 0;
              userAnswers = new Array(data.questions.length).fill(null);

              document.getElementById("module-name").textContent = currentModule.name;

              moduleContainer.classList.add("hidden");
              quizContainer.classList.remove("hidden");

              timeLeft = currentModule.time_limit * 60;
              startTimer();

              displayQuestion(0);
            })
            .catch(error => {
              console.error('Error fetching quiz data:', error);
              alert('Error loading quiz. Please try again.');
            });
        })
        .catch(error => {
          console.error('Error checking module status:', error);
          alert('Error checking module status. Please try again.');
        });
    }

    function submitQuiz() {
      if (quizTimer) {
        clearInterval(quizTimer);
      }

      let correctCount = 0;
      const results = {
        score: 0,
        passed: false,
        questions: [],
        answersMap: {}
      };

      moduleData.questions.forEach((question, index) => {
        const userAnswer = userAnswers[index];
        let isCorrect = false;
        let correctOptionIndex = -1;

        question.options.forEach((option, optIndex) => {
          if (option.is_correct) {
            correctOptionIndex = optIndex;
          }
        });

        isCorrect = userAnswer === correctOptionIndex;

        if (isCorrect) {
          correctCount++;
        }

        results.questions.push({
          question: question.question_text,
          user_answer: userAnswer !== null ? question.options[userAnswer].option_text : "No answer",
          correct_answer: question.options[correctOptionIndex].option_text,
          correct: isCorrect,
        });

        if (userAnswer !== null) {
          results.answersMap[question.question_id] = question.options[userAnswer].option_id;
        }
      });

      results.score = Math.round(
        (correctCount / moduleData.questions.length) * 100
      );
      results.passed = results.score >= currentModule.passing_score;

      fetch('submit_quiz.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            module_id: currentModule.module_id,
            score: results.score,
            answers: results.answersMap
          }),
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error('Error submitting quiz:', data.error);
            if (data.error === 'Already attempted') {
              alert(data.message);
              document.getElementById("back-to-modules").click();
              return;
            }
          }
          displayResults(results);
        })
        .catch(error => {
          console.error('Error submitting quiz:', error);
          alert('Error submitting quiz. Please try again.');
        });
    }

    function displayQuestion(index) {
      const questionsContainer = document.getElementById("questions-container");
      const currentQuestion = moduleData.questions[index];

      const progressBar = document.getElementById("progress-bar");
      const progressPercentage =
        ((index + 1) / moduleData.questions.length) * 100;
      progressBar.style.width = `${progressPercentage}%`;

      questionsContainer.innerHTML = "";

      const questionElement = document.createElement("div");
      questionElement.className = "question-container";

      const questionText = document.createElement("h3");
      questionText.innerHTML = `Question ${index + 1}: ${currentQuestion.question_text}`;
      questionElement.appendChild(questionText);

      const optionsContainer = document.createElement("div");
      optionsContainer.className = "options";

      currentQuestion.options.forEach((option, optionIndex) => {
        const optionElement = document.createElement("div");
        optionElement.className = "option";
        if (userAnswers[index] === optionIndex) {
          optionElement.classList.add("selected");
        }
        optionElement.textContent = option.option_text;
        optionElement.addEventListener("click", () =>
          selectOption(index, optionIndex)
        );
        optionsContainer.appendChild(optionElement);
      });

      questionElement.appendChild(optionsContainer);
      questionsContainer.appendChild(questionElement);

      updateNavigation();
    }

    function selectOption(questionIndex, optionIndex) {
      userAnswers[questionIndex] = optionIndex;

      const options = document.querySelectorAll(".option");
      options.forEach((option) => {
        option.classList.remove("selected");
      });

      options[optionIndex].classList.add("selected");

      checkAllAnswered();
    }

    function updateNavigation() {
      const prevBtn = document.getElementById("prev-btn");
      const nextBtn = document.getElementById("next-btn");
      const submitBtn = document.getElementById("submit-quiz");

      prevBtn.style.visibility =
        currentQuestionIndex === 0 ? "hidden" : "visible";

      if (currentQuestionIndex === moduleData.questions.length - 1) {
        nextBtn.style.visibility = "hidden";
        submitBtn.classList.remove("hidden");
      } else {
        nextBtn.style.visibility = "visible";
        submitBtn.classList.add("hidden");
      }
    }

    function checkAllAnswered() {
      const allAnswered = userAnswers.every((answer) => answer !== null);
      const submitBtn = document.getElementById("submit-quiz");

      if (
        allAnswered &&
        currentQuestionIndex === moduleData.questions.length - 1
      ) {
        submitBtn.classList.remove("hidden");
      }
    }

    function startTimer() {
      if (quizTimer) {
        clearInterval(quizTimer);
      }

      updateTimerDisplay();

      quizTimer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();

        if (timeLeft <= 0) {
          clearInterval(quizTimer);
          submitQuiz();
        }
      }, 1000);
    }

    function updateTimerDisplay() {
      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      document.getElementById("time-remaining").textContent = `${minutes
          .toString()
          .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
    }

    function displayResults(results) {
      quizContainer.classList.add("hidden");
      resultsContainer.classList.remove("hidden");

      document.getElementById("result-module-name").textContent =
        currentModule.name;
      document.getElementById("score").textContent = results.score;

      const passFailElement = document.getElementById("pass-fail");
      if (results.passed) {
        passFailElement.innerHTML = '<i class="fas fa-check-circle fa-lg" style="color: var(--success);"></i> Congratulations! You passed the test.';
        passFailElement.className = "correct";
      } else {
        passFailElement.innerHTML = `<i class="fas fa-times-circle fa-lg" style="color: var(--danger);"></i> You did not pass the test. Required: ${currentModule.passing_score}%. Keep practicing!`;
        passFailElement.className = "incorrect";
      }

      const feedbackContainer = document.getElementById("feedback-container");
      feedbackContainer.innerHTML = '<h3><i class="fas fa-clipboard-check"></i> Question Review</h3>';

      results.questions.forEach((question, index) => {
        const questionElement = document.createElement("div");
        questionElement.className = "feedback-question";

        const questionText = document.createElement("h4");
        questionText.innerHTML = `Question ${index + 1}: ${
            question.question
          }`;
        questionElement.appendChild(questionText);

        const userAnswer = document.createElement("p");
        userAnswer.innerHTML = `<span class="answer-label">Your answer:</span> <span class="${
            question.correct ? "correct" : "incorrect"
          }">${question.user_answer}</span>`;
        questionElement.appendChild(userAnswer);

        if (!question.correct) {
          const correctAnswer = document.createElement("p");
          correctAnswer.innerHTML = `<span class="answer-label">Correct answer:</span> <span class="correct">${question.correct_answer}</span>`;
          questionElement.appendChild(correctAnswer);
        }

        feedbackContainer.appendChild(questionElement);
      });
    }

    document.getElementById("prev-btn").addEventListener("click", () => {
      if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        displayQuestion(currentQuestionIndex);
      }
    });

    document.getElementById("next-btn").addEventListener("click", () => {
      if (currentQuestionIndex < moduleData.questions.length - 1) {
        currentQuestionIndex++;
        displayQuestion(currentQuestionIndex);
      }
    });

    document
      .getElementById("submit-quiz")
      .addEventListener("click", submitQuiz);

    document
      .getElementById("back-to-modules")
      .addEventListener("click", () => {
        currentModule = null;
        currentQuestionIndex = 0;
        userAnswers = [];

        if (quizTimer) {
          clearInterval(quizTimer);
        }

        moduleContainer.classList.remove("hidden");
        quizContainer.classList.add("hidden");
        resultsContainer.classList.add("hidden");
      });
  </script>
</body>

</html>