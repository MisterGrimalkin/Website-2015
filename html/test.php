<html>

<body>
<?php
    function fuck() {
        echo "Right off";
    }
    echo $a;
    $a = 5;
    fuck();
?>

Whoa!

<?php
    echo $a;
    fuck();
    echo $_SERVER['DOCUMENT_ROOT'];
?>

</body>
</html>