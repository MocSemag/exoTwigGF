<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$templates = $twig->load('index.twig');
//~ URL appelée nous retournant des données au format JSON

$form = '<form action="http://oh.my03.com" style="text-align:center" method="post" class="form-example">
  <div class="form-example">
    <label for="name">Combien de profils?: </label>
    <input type="text" name="nbr" value="3" id="name" >
    <input type="submit" value="go!">
  </div>
</form>
';
$nbr = 3;
if (isset($_POST['nbr']) && is_numeric($_POST['nbr'])) {
    $nbr = $_POST['nbr'];
    $data_url = 'http://api.randomuser.me/?results=' . $nbr;
} else
    $data_url = 'http://api.randomuser.me/?results=3';

//~ On appelle l'URL et on stocke le contenu retourné dans une variable
$data_contenu = file_get_contents($data_url);

//~ Les données étant au format JSON, on les décode pour les stocker sous la forme d'un tableau associatif
$data_array = json_decode($data_contenu, true);

//~ On pointe directement sur les données du/des utilisateur(s) retourné(s)
$utilisateurs = $data_array['results'];


echo $templates->render(
    [
        'utilisateurs' => $utilisateurs,
        'form' => $form
    ]
);
?>