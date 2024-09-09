<?php
include('../../includes/dbcon.php');
include('../vendor/vendor/autoload.php');
use Sentiment\Analyzer;

// Assuming $con is your database connection

// Function to store the comment in the database
function storeComment($name, $email, $comment, $neg, $neu, $pos, $compound, $sentiment, $con) {
    // Use prepared statements to prevent SQL injection
    $sql = $con->prepare("INSERT INTO sentiment_analysis (name, email, comment, neg, neu, pos, comp, sentiment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssssss", $name, $email, $comment, $neg, $neu, $pos, $compound, $sentiment);

    if ($sql->execute()) {
        echo "Thank you for your feedback!";
        header("Location: https://xclusiveautospa.site/index.php");
        exit(0);
    } else {
        echo "Error: " . $sql->error;
        header("Location: https://xclusiveautospa.site/index.php");
        exit(0);
    }
}

$obj = new Analyzer();

$result = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $text = $_POST['textarea'];

    $result = $obj->getSentiment($text);
    $neg = $result['neg'];
    $neu = $result['neu'];
    $pos = $result['pos'];
    $compound = $result['compound'];

    // Check sentiment and store in the database
    if ($compound <= -0.05) {
        storeComment($name, $email, $text, $neg, $neu, $pos, $compound, 'negative', $con);
    } elseif ($compound >= 0.05) {
        storeComment($name, $email, $text, $neg, $neu, $pos, $compound, 'positive', $con);
    } else {
        storeComment($name, $email, $text, $neg, $neu, $pos, $compound, 'neutral', $con);
    }
}

?>