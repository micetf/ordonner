$(document).ready(function () {
    var min = 0,
        max = 10,
        nDecimales = 0,
        croissant = true,
        reussites = 0,
        reponses = 0,
        nombres = [],
        listePropositions = [],
        listeCorrections = [],
        listeReponses = [],
        listeSens = [],
        tri_nombres = function (a, b) {
            if (croissant) {
                return a - b;
            }
            return b - a;
        },
        genererNombres = function () {
            var virgule = new RegExp('[.]'),
                espace2 = new RegExp(/(\d+)(\d\d\d) (\d\d\d)$/),
                espace1 = new RegExp(/(\d+)(\d\d\d)$/),
                p10 = Math.pow(10, nDecimales);

            croissant = (Math.round(Math.random()) === 1);
            if (croissant) {
                $('#sens').text('croissant (du plus petit au plus GRAND)');
            } else {
                $('#sens').text('décroissant (du plus GRAND au plus petit)');
            }

            listeSens.push(croissant);

            nombres = [];

            (function () {
                var nouveau, i, j;

                for (i = 0; i < 6; i += 1) {
                    do {
                        nouveau = true;
                        nombres[i] = min + Math.round(Math.random() * p10 * (max - (min + 1))) / p10;
                        for (j = 0; j < i; j += 1) {
                            if (nombres[i] === nombres[j]) {
                                nouveau = false;
                            }
                        }
                    } while (!nouveau);
                }
            }());

            (function () {
                var i, j, k, prop;
                if (p10 === 100) {
                    i = Math.round(Math.random() * 5);
                    do {
                        j = Math.round(Math.random() * 5);
                    } while (i === j);

                    if ((nombres[i] * 100) % 10 > 0) {
                        prop = Math.floor(nombres[i]) + Math.round(Math.random() * 9) / 10;
                        for (k = 0; k < 6; k += 1) {
                            if (nombres[k] === prop) {
                                prop = nombres[j];
                            }
                        }
                        nombres[j] = prop;
                    }
                }
            }());

            $('#etiquettes td').each(function () {
                var nbre = nombres[$(this).index()];
                nbre = nbre.toString().
                    replace(virgule, ',').
                    replace(espace1, "$1 $2").
                    replace(espace2, "$1 $2");
                $(this).text(nbre);
            });

            listePropositions.push(nombres.join(':'));
            nombres.sort(tri_nombres);
            listeCorrections.push(nombres.join(':'));

            $('#correction td').each(function () {
                var nbre = nombres[$(this).index()];
                nbre = nbre.toString().
                    replace(virgule, ',').
                    replace(espace1, "$1 $2").
                    replace(espace2, "$1 $2");
                $(this).text(nbre);
            });
        },
        fin = function () {
            if (reponses === 10) {
                $('#activite').hide();
                $('#impression').show();
            } else {
                genererNombres();
            }
        };

    $('#consignes input[type=button]').click(function () {
        var entier = new RegExp('N'),
            decimale = new RegExp('D');
        if ($(this).attr('name').search(entier) !== -1) {
            min = 1;
            max = $(this).attr('name').replace(entier, '');
            nDecimales = 0;
            $('#borne').text(' entiers ' + $(this).val());
        } else if ($(this).attr('name').search(decimale) !== -1) {
            min = 0;
            max = 10;
            nDecimales = $(this).attr('name').replace(decimale, '');
            $('#borne').text(' décimaux dont la partie décimale est composée de ' + $(this).val());
        }
        reussites = 0;
        reponses = 0;
        $('.reponses').text(reponses);
        $('.reussites').text(reussites);

        listePropositions = [];
        listeCorrections = [];
        listeReponses = [];

        genererNombres();
        $('#consignes').hide();
        $('#impression').hide();
        $('#activite').show();
    });
    $('#etiquettes tr').sortable(
        {
            helper: "clone",
            axis: "x"
        }
    );
    $('input[name=valider]').click(function () {
        var pattern,
            remplace,
            old,
            erreur = false,
            tri = [];
        if (nDecimales !== 0) {
            pattern = new RegExp(',');
            remplace = '.';
        } else {
            pattern = new RegExp(' ', 'g');
            remplace = '';
        }

        old = parseFloat($('td:eq(0)').text().replace(pattern, remplace));

        tri.push(old);

        (function () {
            var i, nouveau;
            for (i = 1; i < 6; i += 1) {
                nouveau = parseFloat($('td:eq(' + i + ')').text().replace(pattern, remplace));
                if (old > nouveau && croissant) {
                    erreur = true;
                }
                if (old < nouveau && !croissant) {
                    erreur = true;
                }
                old = nouveau;
                tri.push(old);
            }
        }());

        listeReponses.push(tri.join(':'));
        reponses += 1;
        if (erreur) {
            $('#tri').hide();
            $('#faux td').each(function () {
                $(this).text($('#etiquettes td:eq(' + $(this).index() + ')').text());
            });
            $('#erreur').show();
        } else {
            reussites += 1;
            $('input[name=continuer]').click();
        }
        $('.reponses').text(reponses);
        $('.reussites').text(reussites);
    });

    $('input[name=abandonner]').click(function () {
        $('#activite, #impression').hide();
        $('#consignes').show();
    });

    $('input[name=recommencer]').click(function () {
        reussites = 0;
        reponses = 0;
        $('.reponses').text(reponses);
        $('.reussites').text(reussites);

        listePropositions = [];
        listeCorrections = [];
        listeReponses = [];

        genererNombres();
        $('#consignes, #impression').hide();
        $('#activite').show();
    });

    $('input[name=continuer]').click(function () {
        $('#erreur').hide();
        fin();
        $('#tri').show();
    });
    $('input[name=imprimer]').click(function () {
        if ($('input[name=prenom]').val().length === 0) {
            $('input[name=prenom]').val('Anonyme');
        }
        $('input[name=max]').val(max);
        $('input[name=decimales]').val(nDecimales);
        $('input[name=propositions]').val(listePropositions.join('|'));
        $('input[name=corrections]').val(listeCorrections.join('|'));
        $('input[name=reponses]').val(listeReponses.join('|'));
        $('input[name=sens]').val(listeSens.join('|'));
        $('#imprimer').submit();
    });
    $('#contact').contact();
});
