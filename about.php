<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meal Match - About Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <?php
    include('config.php');
    include('includes/header.php');
    ?>
 
  <main class="max-w-6xl mx-auto p-6 text-center">
      <h2 class="text-3xl font-bold text-gray-800">About Meal Match</h2>
      <p class="text-lg text-gray-600 mt-2">Connecting food enthusiasts with delicious recipes that cater to every taste.</p>
      <p class="text-lg text-gray-600 mt-4">Whether you're a beginner or a seasoned chef, you'll find inspiration at Meal Match.</p>
      
      <h3 class="text-2xl font-semibold text-gray-700 mt-8">Meet the Team</h3>
      <p class="text-md text-gray-600 mt-2">Meal Match was built as a university team project by a group of dedicated students passionate about web development and problem-solving.</p>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Ahmed</h4>
              <p class="text-gray-600">Team Leader & Minute Keeper</p>
              <p class="text-gray-500 text-sm">Ahmed managed the project timeline and ensured smooth coordination between team members.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Fahid</h4>
              <p class="text-gray-600">Report Writer & Development Help</p>
              <p class="text-gray-500 text-sm">Fahid contributed to report writing and assisted in the website's development process.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Krishna</h4>
              <p class="text-gray-600">Frontend & Backend Developer</p>
              <p class="text-gray-500 text-sm">Krishna worked on both the frontend design and backend functionality of the website.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Hasan</h4>
              <p class="text-gray-600">Report Writer & Researcher</p>
              <p class="text-gray-500 text-sm">Hasan provided valuable research and documentation for the project.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Kieron</h4>
              <p class="text-gray-600">Researcher</p>
              <p class="text-gray-500 text-sm">Kieron contributed research insights and background information for the website.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Ibrahim</h4>
              <p class="text-gray-600">Report Writer & Researcher</p>
              <p class="text-gray-500 text-sm">Ibrahim helped compile reports and conducted research for Meal Match.</p>
          </div>
          <div class="bg-white p-4 shadow-md rounded-lg">
              <h4 class="text-xl font-semibold">Mohammad Ehtesham</h4>
              <p class="text-gray-600">Backend Developer</p>
              <p class="text-gray-500 text-sm">Mohammad focused on backend functionalities and database integration.</p>
          </div>
      </div>
  </main>

  <?php include('includes/footer.php'); ?>

</body>
</html>
