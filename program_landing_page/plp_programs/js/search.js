    const typesenseInstantsearchAdapter = new TypesenseInstantSearchAdapter({
        server: {
            apiKey: 'bilLvsiWoO1EqcM21L8XrzofmVBYfyB9', // Be sure to use an API key that only allows searches, in production
            nodes: [
                {
                    host: 'typesense.extension.iastate.edu',
                    port: '443',
                    protocol: 'https',
                },
            ],
        },
        // The following parameters are directly passed to Typesense's search API endpoint.
        //  So you can pass any parameters supported by the search endpoint below.
        //  queryBy is required.
        //  filterBy is managed and overridden by InstantSearch.js. To set it, you want to use one of the filter widgets like refinementList or use the `configure` widget.
        additionalSearchParameters: {
            queryBy: 'title,body,field_plp_program_search_terms,children_title,children_body',
        },
    });
    const searchClient = typesenseInstantsearchAdapter.searchClient;

    const search = instantsearch({
        searchClient,
        indexName: 'plp_programs',
    });


    search.addWidgets([
        instantsearch.widgets.searchBox({
            container: '#searchbox',
        }),
        instantsearch.widgets.configure({
            hitsPerPage: 12,
        }),
        instantsearch.widgets.hits({
            container: '#hits',
            templates: {
                item(item) {
                    programImage = '';
                    if (item.field_plp_program_smugmug) {
                      programImage = '<img src ="https://photos.smugmug.com/photos/' + item.field_plp_program_smugmug + '/0/XL/' + item.field_plp_program_smugmug + '-XL.jpg" height="100" alt="" />';
                    }
                    return `
                        <div>
                          ${programImage}
                          <div class="hit-name">
                            <a href="${item.url}">${item._highlightResult.title.value}</a>
                          </div>
                          <!-- <div class="${item.field_plp_program_num_events}_events">Number of events: ${item.field_plp_program_num_events}</div> -->
                        </div>
                      `;
                },
            },
        }),
        instantsearch.widgets.pagination({
            container: '#pagination',
        }),
    ]);

    search.start();
