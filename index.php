<!doctype html>
<html class="no-js" lang="fr">

    <head>

        <meta charset="utf-8">
        <meta name="description" content="Application web permettant aux élèves de s'entraîner/évaluer au classement des nombres dans l'ordre croissant/décroissant." />
        <meta name="keywords" content="maths, école primaire, nombre, numération, ordre" />
        <meta name="author" content="Frédéric MISERY - Version du 22/12/2013"/>
        <meta name="viewport" content="width=device-width">

        <title>Ordre des nombres</title>

        <link rel="stylesheet" href="style.css" type="text/css"/>

    </head>

    <body>

        <header>

            <h1>Ordonner des nombres</h1>
            <p>
                <a href="../nombres" title="Construction du nombre">Construction du nombre</a> -
                Créé par <a href="http://micetf.fr" title="Des Outils Pour La Classe">MiCetF</a> (2013) -
                <a id="contact" href="mailto:machin@truc.fr">contact</a>
            </p>

        </header>

        <section id="consignes">

            <h3>Je sais ranger dans l'ordre les nombres...</h3>

            <h3>
                ... entiers :
                <input type="button" name="N9" value=" &lt; 10 "/>
                <input type="button" name="N99" value=" &lt; 100 "/>
                <input type="button" name="N999" value=" &lt; 1000 "/>
                <input type="button" name="N9999" value=" &lt; 10 000 "/>
                <input type="button" name="N99999" value=" &lt; 100 000 "/>
                <input type="button" name="N999999" value=" &lt; 1 000 000 "/>
            </h3>

            <h3>
                ... décimaux avec une partie décimale composée de :
                <input type="button" name="D1" value=" 1 chiffre "/>
                <input type="button" name="D2" value=" 2 chiffres "/>
            </h3>

        </section>
        <section id="activite">

            <h3>
                Range les nombres suivants dans l'ordre&nbsp;
                <span id="sens"></span>.
            </h3>

            <section id="tri">

                <table id="etiquettes">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <p>
                    <input type="button" name="valider" value="Valider"/>&nbsp;
                    <input type="button" name="abandonner" value="Abandonner"/>
                </p>

            </section>

            <section id="erreur">

                <table id="faux">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <table id="correction">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <h4 class="message">Tu as fait une ou plusieurs erreurs. Regarde la correction dans le tableau vert.</h4>

                <p>
                    <input type="button" name="continuer" value="continuer"/>
                </p>

            </section>

            <h3>
                Nombre de réussites :&nbsp;
                <span class="reussites">0</span> sur&nbsp;
                <span class="reponses">0</span>
            </h3>

        </section>

        <section id="impression">

            <h3>
                Ordonner des nombres&nbsp;
                <span id="borne"></span>.
            </h3>

            <h4>
                Tu as ordonné correctement&nbsp;
                <span class="reussites"></span>&nbsp;
                séries parmi les&nbsp;
                <span class="reponses"></span>&nbsp;
                qui t'ont été proposées.
            </h4>

            <form id="imprimer" action="imprimer.php" method="post">

                <p>
                    Saisis ton prénom :&nbsp;
                    <input type="text" name="prenom" value="Anonyme"/>
                </p>
                <p>
                    <input type="button" name="imprimer" value="Imprimer les résultats"/>&nbsp;
                    <input type="button" name="recommencer" value="Recommencer"/>&nbsp;
                    <input type="button" name="abandonner" value="Abandonner"/>
                </p>
                <p>
                    <input type="hidden" name="sens" value=""/>
                    <input type="hidden" name="max" value=""/>
                    <input type="hidden" name="decimales" value=""/>
                    <input type="hidden" name="propositions" value=""/>
                    <input type="hidden" name="corrections" value=""/>
                    <input type="hidden" name="reponses" value=""/>
                </p>

            </form>

        </section>

        <script src="../common/js/jquery.js"></script>
        <script src="../common/js/jqueryui.js"></script>
        <script src="../library/js/jquery.ui.touch-punch.min.js"></script>
        <script src="../library/js/jquery-contact.min.js"></script>
        <script src="ordre.js"></script>

    </body>

</html>