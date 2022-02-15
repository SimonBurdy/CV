<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if($invoice->status != "draft")
        <title>FACTURE n°{{ $invoice->number }}</title>
    @else
        <title>FACTURE Proforma</title>
    @endif


    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap');

        html,
        body {
            margin: 10px 10px 1cm 10px;
            font-family: 'Open Sans', sans-serif;
            font-size: 12px;
            padding-bottom:  2cm;
        }

        .logo {
            text-align: left;
        }

        .address {
            text-align: left;
            width: 80%;

        }

        .address > div {
            text-align: left;
            margin-left: 50px;
        }

        .quote {
            padding: 0px 0px 0px 25px;
            text-align: left;
            margin-top: 50px;
            width: 50%;

        }

        .line {
            text-align: center;
            padding: 0px 10px 0px 10px;
            border-right: 1px solid rgb(190, 187, 187);
            border-bottom: 1px solid rgb(190, 187, 187);

        }

        .table_article {
            width: 100%;
            margin: 15px 0px 0px 0px;
            page-break-after: always;

        }

        .thead_article {
            border : 1px solid rgb(190, 187, 187);
            background-color: lightgray;

        }

        .tbody_article {
            border-right: 1px solid rgb(190, 187, 187);
            border-left: 1px solid rgb(190, 187, 187);
        }

        .result_total {
            background-color: lightgray;
        }

        .page-break {
            page-break-after: always;
        }


        .billing_address_message {
            border: 1px solid red;
            text-align: center;
        }

        .agefiph_discount {
            margin-top : 15px;
        }

        .footer-element {
            white-space: nowrap;
        }







        @page {
        }

        footer {
            border-top: 1px solid rgb(190, 187, 187);
            position: fixed;
            bottom: 0cm;
            left: 0px;
            right: 0px;
            height: 2.5cm;
            text-align: center;
            line-height: 15px;
            margin-top: 20px;
        }



    </style>


</head>


<body>
    <footer>
        <p class="footer-element">  BurdySimonCorporation - SARL BSCorp 20 rue du Carrousel - 59650 Villeneuve d'Ascq <strong> E-mail :</strong> hello@contact.com <strong>
            Téléphone :</strong> +33(0)32039389
        </p>
        <p class="footer-element">
            <strong>Site Internet :</strong> BurdySimon.com <strong>n° SIREN / SIRET :</strong> 527 548 549 00056 <strong>N° TVA UE :</strong>
            FR61527548549 <strong>N° RCS / RM :</strong> Lille B 527 548 549
        </p>
        <p class="footer-element">
            <strong>Banque :</strong> Société Générale <strong>Code banque :</strong> 30003 <strong>N° du compte:</strong> 00020026582
            <strong>Titulaire du compte :</strong> EGD SARL <strong>BIC : </strong> SOGEFRPP
        </p>
        <p>
            <strong>IBAN : </strong>FR76 3000 3002 0900 0250 2658 231
        </p>
    </footer>
        <table  style="width:100%;" >
            <tr rowspan="2">
                <th class="logo"  rowspan="2" >
                    <img style="width: 380px;margin-top: 12px" src="{{ public_path('platform/weezea/img/weezea.png') }}"
                        alt="Logo BurdySimonCorpa">
                </th>
                <td>
                    <span class="text-info" style="font-size: 10px ; color: grey; margin-left:50px">BurdySimonCorp-SARL EGD 20 rue du Carrousel -
                            59650 Villeneuve d'Ascq</span>
                </td>
            </tr>
            <tr>
                <td class="address">
                    <div >
                   
                        @if($invoice->clientAddress)
                            <strong>{{ $invoice->clientAddress->name }} </strong>
                            <p>{!! nl2br(e($invoice->clientAddress->address))!!} </p>
                        @else
                            <strong>{{ $invoice->project->client->name }} </strong>
                            <p>Adresse de facturation à préciser</p>
                        @endif

                    </div>
                </td>
            </tr>
        </table>
        <table style="width:100%;">
            <tr rowspan="2">
                <th rowspan="2">
                        <table style="width:100%; border-collapse:" cellpadding=0 cellspacing=10>
                            <tr>
                                <td colspan="2">
                                    @if( $invoice->status != "draft")
                                        <span>Numéro de facture</span>
                                    @else
                                        <strong>Facture Proforma</strong>
                                    @endif
                                </td>
                                <td>
                                    @if( $invoice->status != "draft")
                                        <span>{{ $invoice->number }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr >
                                <td colspan="2" >
                                    <span>Date de facture</span>
                                </td>

                                <td>
                                    <span>{{ \Carbon\Carbon::parse($invoice->creation_date)->format('d/m/Y')}}</span>
                                </td>
                            </tr>
                            <tr style="margin-top: 3px">
                                <td colspan="2">
                                    <strong>Date d'échance </strong>
                                </td>
                                <td>
                                    <span>  {{ \Carbon\Carbon::parse($invoice->validity_date)->format('d/m/Y') }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </th>
            </tr>

            <tr>
                <td >
                    <div>
                        <strong>Projet n° {{ $invoice->project->project_number }}</strong>
                        <p> {{ $invoice->notes }}</p>
                    </div>
                </td>
            </tr>
               
            
        </table>

      
        <table class="page_break_footer table_article" cellpadding=0 cellspacing=0>
            <thead class="thead_article">
                <tr>
                    <th style="border-right: 1px solid rgb(190, 187, 187)">Description</th>
                    <th class="th_thead_article">Quantité</th>
                    <th class="th_thead_article">Unité</th>
                    <th class="th_thead_article">Prix</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody class="tbody_article">
            @foreach($invoice->rows as $row)

                <tr class="tr_tbody_article" style="">
                    <td class="line" style="text-align: left;">
                        <p>
                            @for ($i = 0; $i < count(explode( PHP_EOL , $row->description )); $i++)

                                @if ($i ==0)
                                    <strong>{{  nl2br(explode( PHP_EOL , $row->description)[$i])  }}</strong> <br>
                                @else
                                    {{ nl2br(explode( PHP_EOL , $row->description)[$i]) }}  <br>
                                @endif
                            @endfor
                        </p>
                    </td>


                    <td class="line">
                        <p>{{ (int)$row->quantity }}</p>

                    </td>
                    <td class="line">
                        <p>{{  __('billing.'.$row->unity) }}</p>
                    </td>
                    <td class="line">

                        <p>
                            {{ number_format($row->unit_price, 2 , ',',' ') }} €
                        </p>

                    </td>
                    <td style="border-bottom: 1px solid rgb(190, 187, 187); text-align: center;">
                        @if($row->discount_euro != "0.00")

                            <p>
                                {{ number_format($row->sell_total+$row->discount_euro, 2 , ',',' ') }} €
                            </p>
                        @else
                            <p>
                                {{ number_format($row->sell_total, 2 , ',',' ') }} €
                            </p>
                        @endif

                    </td>
                </tr>

                @if($row->discount_euro != "0.00")
                    <tr class="tr_tbody_article" style="border-top: 1px solid  rgb(190, 187, 187);">
                        <td class="line" style="text-align: left;">

                            <p>

                                @for ($i = 0; $i < count(explode( PHP_EOL , $row->description )); $i++)

                                    @if ($i ==0)

                                        <strong>Geste commercial</strong>
                            <p>Remise de {{  $row->discount_euro }} pour le
                                produit {{  nl2br(explode( PHP_EOL , $row->description)[$i])  }} d'un montant
                                de {{ number_format($row->sell_total, 2 , ',',' ') }} € HT</p>
                            @endif
                            @endfor
                            </p>
                        </td>
                        <td class="line">
                            @if($row->discount_unit == "pc")
                                <p>{{ number_format(($row->discount_euro  * 100) / ($row->sell_total+$row->discount_euro ) ,2 , ',',' ')}}</p>
                            @else
                                <p>1</p>
                            @endif


                        </td>
                        <td class="line">
                            @if($row->discount_unit == "pc")
                                <p>%</p>
                            @else
                                <p>€</p>
                            @endif
                        </td>
                        <td class="line">

                            <p>
                                X
                            </p>
                        </td>
                        <td style="border-bottom: 1px solid rgb(190, 187, 187); text-align: center;">
                            <p>
                                - {{ number_format($row->discount_euro, 2 , ',',' ') }} €
                            </p>
                        </td>
                    </tr>
                @endif
            @endforeach
            @if($invoice->discount_euro > 0)
                <tr class="tr_tbody_article" style="border-top: 1px solid rgb(190, 187, 187);">
                    <td class="line" style="text-align: left;">
                        <strong>Geste commercial</strong>
                        <p>Remise de {{  $invoice->discount_euro }} pour Facture n° {{ $invoice->number }} d'un montant
                            de {{ $invoice->sell_total }} € HT</p>
                    </td>

                    <td class="line">
                        1
                    </td>

                    <td class="line">
                        €
                    </td>

                    <td class="line">
                        - {{  number_format($invoice->discount_euro, 2 , ',',' ')}} €
                    </td>
                    <td style="border-bottom: 1px solid rgb(190, 187, 187); text-align: center">
                        - {{  number_format($invoice->discount_euro, 2 , ',',' ')}} €
                    </td>
                </tr>
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">


                    </td>
                    <td colspan="3">
                        <table cellpadding=0 cellspacing=0 style="width: 100%">
                            <tr>
                                <td style="width: 70%">
                                    <p>Sous-total HT</p>
                                </td>
                                <td style="text-align: right; padding-right : 3px;">
                                    <p>{{ number_format($invoice->sell_total, 2 , ',',' ') }} €</p>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <p>TVA {{ $invoice->vat }}%  {{ number_format($invoice->sell_total, 2 , ',',' ') }} €</p>
                                </td>
                                <td style="text-align: right ; padding-right : 3px;">    
                                    <p>{{ number_format($invoice->total_vat, 2 , ',',' ') }} €</p>
                                </td>
                                    
                            </tr>
                            <tr class="result_total">
                                <td >
                                    <strong>Montant Total TTC</strong>
                                </td>
                                <td style="text-align: right ; padding-top: 3px ; padding-bottom: 3px; padding-left: 3px;" >
                                    <strong> {{ number_format($invoice->total_with_taxes, 2 , ',',' ') }} € </strong>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

</body>

</html>
