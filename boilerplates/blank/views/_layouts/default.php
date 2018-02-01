<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>µ was here.</title>
    <link rel="stylesheet" href="<?= $paths->public . $this->asset('/assets/css/styles.css') ?>">
    <link rel="manifest" href="<?= $paths->public ?>/manifest.json">
</head>
<body>

    <main>
        <img width="256" src="<?= $paths->public . $this->asset('/assets/images/micro.svg') ?>" alt="micro framework">

        <?= $this->section('content') ?>
    </main>

</body>
</html>


<!-- µ · <?= $pageload() ?> -->

