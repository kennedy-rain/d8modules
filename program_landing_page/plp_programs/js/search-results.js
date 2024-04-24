const typesenseInstantsearchAdapterResults = new TypesenseInstantSearchAdapter({
  server: {
    apiKey: "bilLvsiWoO1EqcM21L8XrzofmVBYfyB9", // Be sure to use an API key that only allows searches, in production
    nodes: [
      {
        host: "typesense.extension.iastate.edu",
        port: "443",
        protocol: "https",
      },
    ],
  },
  // The following parameters are directly passed to Typesense's search API endpoint.
  //  So you can pass any parameters supported by the search endpoint below.
  //  queryBy is required.
  //  filterBy is managed and overridden by InstantSearch.js. To set it, you want to use one of the filter widgets like refinementList or use the `configure` widget.
  additionalSearchParameters: {
    queryBy:
      "title,body,field_plp_program_search_terms,children_title,children_body",
  },
});

const searchClientResults = typesenseInstantsearchAdapterResults.searchClient;
const { infiniteHits } = instantsearch.widgets;

const searchResults = instantsearch({
  searchClient: searchClientResults,
  indexName: "plp_programs",
  routing: true,
});

searchBoxID = "#search-results-bar";
if (document.getElementById('search-bar')) {
  searchBoxID = "#search-bar";
}

searchResults.addWidgets([
  instantsearch.widgets.searchBox({
    container: searchBoxID,
    autofocus: true,
    searchAsYouType: false,
    placeholder: "Search Programs",
  }),
  instantsearch.widgets.configure({
    hitsPerPage: 120,
  }),
  instantsearch.widgets.infiniteHits({
    container: "#hits",
    templates: {
      item(item) {
        imagelink = "";
        if (item.field_plp_program_smugmug) {
          imagelink =
            '<img src ="https://photos.smugmug.com/photos/' +
            item.field_plp_program_smugmug +
            "/0/XL/" +
            item.field_plp_program_smugmug +
            '-XL.jpg" alt="" />';
        }
        return `
          <div>
            <div class="hit-name"><a href="${item.url}">${item.title}</a></div>
            ${imagelink}
            <div class="hit-summary">${item.summary}</div>
          </div>
        `;
      },
    },
  }),
  instantsearch.widgets.stats({
    container: "#stats",
  }),
]);

searchResults.addWidgets([
  instantsearch.widgets.refinementList({
    container: "#category_name",
    attribute: "category_name",
  }),
]);

searchResults.addWidgets([
  instantsearch.widgets.refinementList({
    container: "#topic_names",
    attribute: "topic_names",
  }),
]);

searchResults.addWidgets([
  instantsearch.widgets.refinementList({
    container: "#program_area",
    attribute: "program_area",
  }),
]);

searchResults.start();
