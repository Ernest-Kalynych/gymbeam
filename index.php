<?php
require "vendor/autoload.php";
//ignore deprecated warning, because we dont se emojis in description
error_reporting(E_ALL ^ E_DEPRECATED);

//opening file with product name and descriptions
$CSVvar = fopen("dataset-gymbeam-product-descriptions-eng.csv", "r");
use Sentiment\Analyzer;
$analyzer = new Analyzer();

if ($CSVvar !== FALSE) {
    ?>
    <html>

    <head>
        <style>
            table,
            th,
            td {
                border: 1px solid black;
                padding: 0;
            }
        </style>
    </head>

    <body>
        <table>
            <thead>
                <tr>
                    <th> </th>
                    <th><b>Positivity Coef</b></th>
                    <th><b>Name</b></th>
                    <th><b>Description</b></th>
                </tr>
            </thead>
            <?php
            //product objects
            $mostNegativeProduct = null;
            $mostPositiveProduct = null;

            //known coef-s
            $mostNegativeCoef = null;
            $mostPositiveCoef = null;

            while (!feof($CSVvar)) {
                $data = fgetcsv($CSVvar, 1000, ",");
                if (!empty($data)) {
                    $output_text = $analyzer->getSentiment(strip_tags($data[1]));
                    $coefCompoundScore = $output_text["compound"];
            
                    if ($mostNegativeCoef == null || $coefCompoundScore < $mostNegativeCoef) {
                        $mostNegativeProduct = $data;
                        $mostNegativeCoef = $coefCompoundScore;
                    }
            
                    if ($mostPositiveCoef == null || $coefCompoundScore > $mostPositiveCoef) {
                        $mostPositiveProduct = $data;
                        $mostPositiveCoef = $coefCompoundScore;
                    }
                }
            }
            
            ?>
            <tr>
                <td>Most Negative</td>
                <td>
                    <?php echo $mostNegativeCoef ?>
                </td>
                <td>
                    <?php echo $mostNegativeProduct[0] ?>
                </td>
                <td>
                    <?php echo $mostNegativeProduct[1] ?>
                </td>
            </tr>
            <tr>
                <td>Most positive</td>
                <td>
                    <?php echo $mostPositiveCoef ?>
                </td>
                <td>
                    <?php echo $mostPositiveProduct[0] ?>
                </td>
                <td>
                    <?php echo $mostPositiveProduct[1] ?>
                </td>
            </tr>
            <?php
}
else
{
    echo "Unable to open file: dataset-gymbeam-product-descriptions-eng.csv";
}
?>
    </table>
</body>

</html>
<?php
fclose($CSVvar);
?>