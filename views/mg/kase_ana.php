<?php
$anaQuestions['na_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Finalement, pour quelle formule de voyage avez-vous opté ?',
    '5. Pour quelles raisons vous n\'avez pas choisi Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)',
];

$anaQuestions['a_160730'] = [
    '0. Time',
    '1. Comment avez-vous connu Amica Travel ?',
    '2. Pour ce voyage avez-vous également contacté d\'autres agences de voyage ?',
    '3. Si oui, pouvez-vous préciser leurs noms ?',
    '4. Pourquoi avez-vous choisi une agence locale, plutôt qu\'une de votre pays ?',
    '5. Qu\'est-ce qui vous a convaincu de choisir finalement Amica Travel ?',
    '6. Quels sont vos commentaires sur les différents échanges entre vous et l\'équipe d\'Amica Travel ?',
    '7. Avez-vous certaines remarques ou suggestions qui nous permettraient d\'améliorer notre service ?',
    '8. Votre nom et/ou votre adresse de mail (pas obligatoire)'
];
?>
<div style="width:600px;">
	<p>Link to case: <a href="https://my.amicatravel.com/cases/r/<?= $theCase['id'] ?>">https://my.amicatravel.com/cases/r/<?= $theCase['id'] ?></p>
<?php

$parts = explode('[#QA]', $anaString);

for ($i = 1; $i <= count($anaQuestions[$parts[0]]); $i ++) { ?>
        <div style="font-weight:bold;"><?= $anaQuestions[$parts[0]][$i - 1] ?></div>
        <div><?= isset($parts[$i]) ? nl2br(trim($parts[$i])) : '' ?></div>
        <br>
<?php
}

?>
</div>