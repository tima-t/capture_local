<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
<div style="text-align:center">

      <div class="">
        <?php if(isset($_SESSION["fails"]) && $_SESSION["fails"]>0 ): ?>
        <p>You have <?= (3-$_SESSION["fails"]) ?>  more try!</p>
        <?php endif ?>
        <img src="<?=$_SESSION['image_src'] ?>" alt="" />
      </div>
      <form action="login.php" method="POST">
      capture: <input type="text" name="capt" required="1" /><br>
      <input type="submit">
      </form>
</div>
  </body>
</html>
