<ul class="nav nav-pills">
  <li class="nav-item">
    <a class="nav-link <?php echo($_GET['page'] == 1) ? ' active' : '' ?>"
         href="index.php?page=1">Петиции</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo($_GET['page'] == 2) ? ' active' : '' ?>" 
        href="index.php?page=2">Добавить петицию</a>
  </li>
</ul>
