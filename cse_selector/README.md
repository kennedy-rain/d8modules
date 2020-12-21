# CSE Selector Module
### cse_selector
This Drupal 8 module provides a search-bar and a search results page that implements the Google Custom search engine with customization.

### Search bar
The module creates a block that uses the responsive classes found in the Extension theme.
The search bar consists of a [Form](src/Form/CSESearchForm.php) put into a [Plugin Block](src/Plugin/Block/CSESearchBlock.php) that currently needs to be manually added to the site.

### Results Page
The module automatically generates a page that consists of a [Form](src/Form/ResultsForm.php) put into a [Controller](src/Plugin/Block/CSESearchBlock.php) at the url defined by the [Routing File](cse_selector.routing.yml).

### Configuration
The module creates a settings page that can be accessed under Configuration>Content Authoring>CSE Selector Settings after the module has been installed. This allows the search engine ID, Narrow & Wide search text, URL for Narrow Searches, Default search behavior, URL parameter name for query string and the Route for the search results to appear on.
