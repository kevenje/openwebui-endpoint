<?PHP
//This code uses CURL to query an https://openwebui.com/ instance to generate a boolean search string from a user provided research query.
//3/17/2025

$searchQuery = "What is the effect of social media on mental health?";

$apiKey = ''; //key or path to key

// API endpoint URL
$apiUrl = 'https://[Your Domain]/ollama/api/generate';
$ai_model = 'llama3.1:70b';

// Request headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer '. $apiKey
];


$promptInput = 'You are a progressive librarian helping researchers construct a search of an article database using Boolean operators (i.e. AND, OR, NOT), parentheses, truncation with asterisk, and quotes. The only output should be a Boolean search string. Never provide any commentary or explanation or note explaining actions. For the user provided research query: "' . $searchQuery . '" do the following: Correct spelling and grammar. Remove quotation marks and symbols. Remove references to material types like books, articles, reviews, etc. Remove filler and stop words. Remove references to publication dates. Generalize historical time periods. Generalize ages (e.g. use child instead of "age 5"). Put multiple word terms in quotes, for example “20th Century”. Always enclose a phrase of two or more words in double quotes, for example "social network". Never quote a single word term, for example (“social”). Do not truncate possessive names, for example "Alzheimer’s Disease". For a personal name, write the first and last name together in quotes, then follow it with the last name, first name in quotes, all within parentheses joined by OR (e.g. “Charles Darwin” OR “Darwin, Charles”). Use OR to connect synonyms, for example Cats OR Dogs. Exclude unwanted terms with NOT, instead of a minus sign, such as Children NOT Adult. Identify keywords and select the most common single word term synonyms for each keyword.  Include the synonyms along with the original keyword in the search. Include related scientific terms or scientific names as a quoted phrase, for example “pan troglodytes”.  Group synonyms with parentheses, for example (Students OR Scholars). Never truncate words in quoted phrases. For single terms only, reduce words to their root by stemming and apply wildcard truncation with an asterisk (*) to capture plurals, variations, and related terms (e.g., crime would match crime, crimes, criminal, criminals, etc.). Here is an example of how the process should run: "How can librarians prepare themselves for using artificial intelligence in the workplace?" will give the output: (Librar* OR Librarian* OR "Library Staff" OR "Information Professional") AND (Prepar* OR Train* OR Educ* OR Develop* OR Read*) AND ("Artificial Intelligence" OR "Machine Learning" OR "Large Language Model") AND ("Work Environment" OR "Professional Setting" OR Workplac*) NOT (Student* OR Patron*). Prior to outputting the response verify that every multiple word phrase or multiple word name or multiple word term are enclosed in double quotes. Never provide any commentary or a note or description or correction of your process. Do not ask any questions. END the output after the first boolean search string has been created.';
    

$data = [
    'model' => $ai_model,
    'prompt' => $promptInput,
    'max_tokens' => 300,
    'temperature' => 0.9,
    'top_p' => 0.9,
    'stream' => false,
    'new_chat' => true
];

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the API request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Process the API response
if ($response) {
    $responseData = json_decode($response, true);
    // Extracting output variables
    $model = $responseData['model'];
    $createdAt = $responseData['created_at'];
    $assistantResponse = $responseData['response'];

    // Extracting output variables
    $done = $responseData['done'];
    $doneReason = $responseData['done_reason'];
    $context = $responseData['context'];
    $totalDuration = $responseData['total_duration'];
    $loadDuration = $responseData['load_duration'];
    $promptEvalCount = $responseData['prompt_eval_count'];
    $promptEvalDuration = $responseData['prompt_eval_duration'];
    $evalCount = $responseData['eval_count'];
    $evalDuration = $responseData['eval_duration'];

echo 'Your Question: '. $searchQuery.'<br>';
echo 'AI Response: '. $assistantResponse;

} else {
    echo 'Error: No response from the API';
}
?>
