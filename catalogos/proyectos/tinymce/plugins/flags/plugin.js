(function () {
    let flags = (function () {
        'use strict';

        tinymce.PluginManager.add("flags", function (editor, url) {

            editor.ui.registry.addAutocompleter('autocompleter-flags', {
                ch: '@', // the trigger character to open the autocompleter
                minChars: 2, // lower number means searching sooner - but more lookups as we go
                columns: 1, // must be 1 for text-based results
                fetch: function (pattern) {
                    return new tinymce.util.Promise(function (resolve) {
                        // call the countries REST endpoint to look up the query, and return the name and flag
						
						//'https://restcountries.eu/rest/v2/name/' + pattern + '?fields=name;flag'
                        fetch('http://localhost/tasksManager/catalogos/proyectos/usuarios.php?nombre=' + pattern)
                            .then((resp) => resp.json()) // convert response to json
                            .then(function (data) {
                                let results = [];
										//console.log(data);
                                // create our own results array
                                for (let i = 0; i < data.length; i++)
                                {
                                    let result = data[i];

                                    results.push({
                                        value: result.nombre_usuario + '|' + result.PKUsuario,
                                        text: result.nombre_usuario//texto que aparece en la busqueda
                                    });
                                }

                                // sort results by the "name"
                                results.sort(function (a, b) {
                                    let x = a.text.toLowerCase();
                                    let y = b.text.toLowerCase();
                                    if (x < y)
                                    {
                                        return -1;
                                    }
                                    if (x > y)
                                    {
                                        return 1;
                                    }
                                    return 0;
                                });

                                // resolve the initial promise
                                resolve(results);
                            });
                    });
                },
                onAction: function (autocompleteApi, rng, value) {
                    // split the value in to two parts - the name and the flag URL
                    // we joined it above using a pipe (|)
                    let parts = value.split('|');
                    let name = parts[0];
                    let id = parts[1];
					
					//console.log("alfombra " + name + "/// leo" +flag);
                    // make an image element
                    //let img = '<img src="' + flag + '" alt="' + name + '" width="48" height="24" class"bella">';
					let agregado = '<span class="mymention" contenteditable="false" data-mention-id="' + id + '" data-mce-mentions-id="' + name + '">' + name + '</span>';
					

                    // insert in to the editor
                    editor.selection.setRng(rng);
                    editor.insertContent(agregado);

                    // hide the autocompleter
                    autocompleteApi.hide();
                }
            });

            // return metadata for the Help plugin
            return {
                getMetadata: function () {
                    return {
                        name: "Flags Autocompleter example",
                        url: "https://www.martyfriedel.com/blog/tinymce-5-creating-an-autocomplete-plugin"
                    };
                }
            };
        });
    }());
})();