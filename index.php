<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig   = new \Twig\Environment($loader);

$templates = $twig->load('index.twig');
//~ URL appelée nous retournant des données au format JSON

echo '<form action="http://localhost" method="post" class="form-example">
  <div class="form-example">
    <label for="name">Combien de profils?: </label>
    <input type="text" name="nbr" value="3" id="name" >
    <input type="submit" value="go!">
  </div>
</form>
';
$nbr=3;
if (isset($_POST['nbr']) && is_numeric($_POST['nbr'])){
    $nbr=$_POST['nbr'];
    $data_url = 'http://api.randomuser.me/?results='.$nbr;
}else
$data_url = 'http://api.randomuser.me/?results=3';

//~ On appelle l'URL et on stocke le contenu retourné dans une variable
$data_contenu = file_get_contents($data_url);

//~ Les données étant au format JSON, on les décode pour les stocker sous la forme d'un tableau associatif
$data_array = json_decode($data_contenu, true);

//~ On pointe directement sur les données du/des utilisateur(s) retourné(s)
$utilisateurs = $data_array['results'];


function contentMaker($utilisateurs)
{
    $result='';
                    foreach( $utilisateurs as $utilisateur){

                $string='<div class="card col-4">';
                $string=$string.'<img class="card-img-top" src="'. $utilisateur['picture']['medium'].'" alt="Image de' .  $utilisateur['name']['first'].'"/>';
                $string=$string. '<div class="card-body">';
                $string=$string.'<h5 class="card-title">';
                $string=$string.'Bonjour, mon nom est '. ucfirst($utilisateur['name']['first'])." ".strtoupper($utilisateur['name']['last']);
                $string=$string. '</h5>';
                $string=$string.'<p class="card-text">';
                $string=$string.' J\'habite "'. strtoupper($utilisateur['location']['state']).'", dans une ville qui s\'appelle "'. strtoupper($utilisateur['location']['city']).'"<br><br>';
                $string=$string.'</p><p class="card-text"><small class="text-muted">Mon adresse e-mail est <a href="mailto:'.$utilisateur['email'].' title="Envoyer un email à'. ucfirst($utilisateur['name']['first']).'">'. $utilisateur['email'].'</a>, dont le mot de passe est "'.$utilisateur['login']['password'].'"';
                $string=$string.'</small></p></div></div>';
                $result=$result.$string;
    }
                    return utf8_encode($result);
             }

echo $templates->render(
[
        'content' => contentMaker($utilisateurs)

]
);
?>
