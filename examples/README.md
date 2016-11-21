PHP Examples
============

These examples are scripts that can be run independently to demonstrate the Rosette API functionality.

You can now run your desired _endpoint_.php file to see it in action.  Before running the examples 
for the first time:
1. cd examples
2. composer update

For example, run `php categories.php` if you want to see the categories functionality demonstrated.

All files require you to input your Rosette API User Key after `--key` to run.
For example: `php ping.php --key 1234567890`  
All also allow you to input your own service URL if applicable.  
For example: `php ping.php --key 1234567890 --url http://www.myurl.com`


Each example, when run, prints its output to the console.

| File Name                     | What it does                                          | 
| -------------                 |-------------                                        | 
| categories.php                    | Gets the category of a document at a URL              | 
| entities.php                      | Gets the entities from a piece of text                | 
| info.php                          | Gets information about Rosette API                    | 
| language.php                      | Gets the language of a piece of text                  | 
| matched-name.php                  | Gets the similarity score of two names                | 
| morphology_complete.php               | Gets the complete morphological analysis of a piece of text| 
| morphology_compound-components.php    | Gets the de-compounded words from a piece of text     | 
| morphology_han-readings.php           | Gets the Chinese words from a piece of text           | 
| morphology_lemmas.php                 | Gets the lemmas of words from a piece of text         | 
| morphology_parts-of-speech.php        | Gets the part-of-speech tags for words in a piece of text | 
| ping.php                          | Pings the Rosette API to check for reachability       | 
| sentences.php                     | Gets the sentences from a piece of text               |
| sentiment.php                     | Gets the sentiment of a local file                    | 
| tokens.php                        | Gets the tokens (words) from a piece of text          | 
| translated-name.php               | Translates a name from one language to another        |

