<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Voucher</title>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inconsolata&family=Open+Sans:wght@300;400;600;800&family=Quicksand:wght@300;400;700&family=Roboto:wght@100;400;700&family=Varela+Round&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ public_path('/style/Invite.css') }}" />
</head>
<body>
    <div id='zaproszenieHeader' style="width: 100%; display: flex; justify-content: center; height: 128px;">
        <img src="{{ public_path('/img/logo.png') }}" style="height: 128px; width: 128px; position: absolute; left: 50%; transform: translateX(-50%)" alt="Logo Papugarni"></img>
    </div>
    <hr/>
    <div>
    <div style="border: 1px solid rgba(0,0,0,0.3); margin-left: 5px;float: left; width: 68%; height: 300px" id='inviteName'>
        <h2 style="line-height: 20px; font-size: 18px; padding-left: 5px; padding-top: 5px">Voucher do papugarni</h2>
        <h3 style="font-weight: 400;line-height: 20px; width: 450px; font-size: 18px; padding-left: 5px">Wejście {{ $type }} do Papugarni Carmen dla {{ $count }} os.</h3>
        <h4 style="font-size: 14px; padding-left: 5px; padding-bottom: 5px; font-weight: 400">Voucher ważny do {{ $expire_date }}</h4>
        <br />
        <h3 style="padding-left: 5px;">Kod Vouchera:</h3>
        <h3 style="font-weight: 400; padding-left: 5px; padding-bottom: 5px;">{{ $invite_code }}</h3>
    </div>
    <div style="border: 1px solid rgba(0,0,0,0.3); float: left; width: 30%; border-left: none; height: 300px;" id="inviteLoc">
        <div style="padding-top: 5px; padding-left: 5px;">
            <img src="{{ public_path('/img/Img.png') }}" style="height: 120px; padding-top: 10px" />
            <div style="display: flex; align-items: center;">
                <p style="padding-left: 5px; padding-top: 5px; ">
                <img src="{{ public_path('/img/Map_pin_icon.png') }}" alt="Pinezka" style="height: 24px;" />
                Wykorzystaj tutaj:</p>
                <p style="font-size: 12px;">Al. Jerozolimskie 200 (Wejście od strony Łopuszańskiej) <br />02-486 Warszawa<br/>
                <a href="https://goo.gl/maps/Lqs5sNkN932vtqhp6">Pokaż na mapie</a>
            </div>
        </div>
    </div>
    <div style="clear: both; padding: 5px">
        <h2 style="font-size: 20px"><img src="{{ public_path('/img/info.png') }}" style="height: 16px;" /> Warunki zaproszeń</h2>
        <ol style="font-size: 14px">
            <li>Vouchery ważne są przez 6 miesięcy od momentu zakupu, obowiązują one przez cały tydzień.</li>
            <li>Voucher nie upoważnia do wejścia przed kolejką.</li>
            <li>W voucherze nie jest zawarta karma dla papug, zakup możliwy przy kasie papugarni.</li>
            <li>Voucher jest do jednokrotnego wykorzystania.</li>
            @if ($type === "ulgowe")
            <li>Voucher ulgowy dotyczy dzieci do 16 roku życia, uczniów, studendów do 26 roku życia.</li>
            @endif
        </ol>
    </div>
    <hr />
    <p style="font-size: 12px; padding: 5px; text-align: center">W razie pytań prosimy o kontakt telefoniczny <a href="tel:+48506059999">+48 506 059 999</a>, bądź kontakt mailowy <a href="mailto:biuro.papugarnia@gmail.com">biuro.papugarnia@gmail.com</a>
</body>
</html>
