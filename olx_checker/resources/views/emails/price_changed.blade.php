<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зміна ціни оголошення</title>
</head>
<body>
    <h1>Ціна оголошення змінилась!</h1>

    <p>Ви підписались на відстеження оголошення за посиланням:</p>
    <p><a href="{{ $adUrl }}">{{ $adUrl }}</a></p>

    <p><strong>Стара ціна:</strong> {{ $oldPrice }} грн</p>
    <p><strong>Нова ціна:</strong> {{ $newPrice }} грн</p>

    <p>Щоб дізнатися більше деталей, будь ласка, перейдіть за посиланням на оголошення.</p>
</body>
</html>
