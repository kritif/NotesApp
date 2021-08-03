<html lang="pl">

<head>
  <title>Notepad</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
  <link href="/public/style.css" rel="stylesheet">
</head>

<body class="body">
  <div class="wrapper">
    <header class="header">
      <h1><i class="far fa-clipboard"></i>Note App</h1>
    </header>
    <div class="container">
      <nav class="menu">
        <ul>
          <li>
            <a href="/">Note list</a>
          </li>
          <li>
            <a href="/?action=create">New note</a>
          </li>
        </ul>
      </nav>
      <div class="page">
        <?php
        require_once("templates/pages/$page.php");
        ?>
      </div>
    </div>
    <footer class="footer">
    <p>NoteApp powered by PHP</p>
    </footer>
  </div>
</body>

</html>