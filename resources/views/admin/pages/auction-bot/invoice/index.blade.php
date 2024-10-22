<style type="text/css">
    body,
    div,
    table,
    thead,
    tbody,
    tfoot,
    tr,
    th,
    td,
    p {
        font-family: "Arial";
        font-size: x-small
    }

    td,
    th {
        vertical-align: middle;
    }

    a.comment-indicator:hover+comment {
        background: #ffd;
        position: absolute;
        display: block;
        border: 1px solid black;
        padding: 0.5em;
    }

    a.comment-indicator {
        background: red;
        display: inline-block;
        border: 1px solid black;
        width: 0.5em;
        height: 0.5em;
    }

    comment {
        display: none;
    }
</style>
@foreach ($loans as $loan)
    <table cellspacing="0" border="0" style="">
        <tr>
            <td width=11></td>
            <td width=2></td>
            <td width=10></td>
            <td width=2></td>
            <td width=63></td>
            <td width=8></td>
            <td width=8></td>
            <td width=8></td>
            <td width=20></td>
        </tr>
        <tr>
            <td height="30" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=10> INVOICE </font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td colspan=4 rowspan=3 align="left" valign=middle>
                <font face="Verdana" size=4><br>
                    {{-- <img src="{{ asset('/img/picture1.png') }}" width=28 height=63 margin=82> --}}
                    <!-- <img src="https://onelitokoi.id/img/picture1.png" width=28 height=63 margin=82> -->
                </font>
            </td>
        </tr>
        <tr>
            <td height="26" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=4> No: INV/{{ $loan->end_time }}/{{ $loan->id }} </font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="26" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=4> Date : {{ $loan->end_time }} </font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="20" align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left">
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
        </tr>
        <tr>
            <td height="21" align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4 color="#333333"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="26" align="left" valign=middle>
                <font face="Verdana" size=3>To </font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=3>: </font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=3>{{ $loan->full_name }}</font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="26" align="left" valign=top>
                <font face="Verdana" size=3>Alamat</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3>:</font>
            </td>
            <td align="left" valign=top rowspan=2>
                <font face="Verdana" size=3><br>{{ $loan->alamat_tinggal }}</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="27" align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="27" align="left" valign=middle>
                <font face="Verdana" size=3>Telephone</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3>:</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3>{{ $loan->no_telp }}</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="17" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 3px double #000000; border-bottom: 3px double #000000; border-left: 3px double #000000; vertical-align: middle; text-align: center;"
                colspan=5 rowspan=2 height="38" bgcolor="#C0C0C0"><b>
                    <font face="Verdana" size=3>D e s c r i p t i o n</font>
                </b></td>
            <td style="border-top: 3px double #000000; border-bottom: 3px double #000000; border-right: 1px solid #000000;"
                colspan=3 rowspan=2 align="center" valign=middle bgcolor="#C0C0C0"><b>
                    <font face="Verdana" size=3>Qty</font>
                </b></td>
            <td style="border-top: 3px double #000000; border-bottom: 3px double #000000; border-left: 1px solid #000000; border-right: 3px double #000000; vertical-align: middle;"
                rowspan=2 align="center" valign=middle bgcolor="#C0C0C0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=3> Amount </font>
                </b></td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana" size=4> </font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"> Payment of :</font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana"><br></font>
                </b>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana">{{ $loan->judul_lelang }}, Nomor Ikan {{ $loan->nomor_ikan }}</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="center" valign=middle sdval="1" sdnum="1033;0;0">
                <font face="Verdana">1</font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"> fish </font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana"><br></font>
                </b>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="right" valign=middle
                sdval="0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"> Rp- {{ number_format($loan->harga_akhir) }} </font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana">Term &amp; Condition :</font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="25" align="left" valign=top>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td colspan=3 align="left" valign=top>
                <font face="Verdana">- Harga terlampir merupakan biaya keeping koi di fasilitas Onelito Koi<br></font>
            </td>
            <td align="left" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="19" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana">- Harga tidak termasuk biaya obat yang diperlukan jika ikan sakit</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="57" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td style="border-bottom: 3px double #000000" colspan=3 rowspan=2 align="left" valign=middle>
                <font face="Verdana">- Resiko terhadap ikan yang sakit hingga kematian pada ikan yang disebabkan oleh
                    hal <br> apapun tidak menjadi tanggung jawab koi keeper (Onelito Koi), baik saat berada di <br>
                    fasilitas Onelito Koi maupun saat terdapat Koi Show</font>
            </td>
            <td align="center" valign=middle sdnum="1033;0;0"><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="left"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana"><br></font>
                </b>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana"><br></font>
                </b></td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="21" align="left" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 3px double #000000; border-left: 3px double #000000" height="24" align="left"
                valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="right" valign=middle>
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="right" valign=middle
                sdnum="1033;1033;MMMM D\, YYYY;@">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_(* #,##0_);_(* \(#,##0\);_(* &quot;-&quot;_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_(* #,##0_);_(* \(#,##0\);_(* &quot;-&quot;_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="center" valign=middle sdnum="1033;0;0">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000; border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
            <td style="border-top: 3px double #000000; border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana"><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="24" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td colspan=3 align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=3> Total </font>
                </b></td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="right" valign=middle
                sdval="0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=3> Rp- {{ number_format($loan->harga_akhir) }}</font>
                </b>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="24" align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td colspan=3 align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3> DP </font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="right" valign=middle
                sdval="0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3> Rp- </font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="24" align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=3><br></font>
            </td>
            <td colspan=2 align="right" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3> DISCOUNT </font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3><br></font>
            </td>
            <td style="border-right: 3px double #000000" align="right" valign=middle sdval="0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3> Rp- </font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="24" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="right" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-right: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-left: 3px double #000000" height="24" align="left" valign=middle
                bgcolor="#C0C0C0">
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle bgcolor="#C0C0C0">
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle bgcolor="#C0C0C0">
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle bgcolor="#C0C0C0">
                <font face="Verdana" size=4><br></font>
            </td>
            <td colspan=3 align="right" valign=middle bgcolor="#C0C0C0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=3> GRAND TOTAL </font>
                </b></td>
            <td style="border-right: 1px solid #000000" align="left" valign=middle bgcolor="#C0C0C0"
                sdnum="1033;0;_([$Rp-421]* #,##0.00_);_([$Rp-421]* \(#,##0.00\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=3><br></font>
            </td>
            <td style="border-left: 1px solid #000000; border-right: 3px double #000000" align="right" valign=middle
                bgcolor="#C0C0C0" sdval="0"
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=3> Rp- {{ number_format($loan->harga_akhir) }}</font>
                </b></td>
        </tr>
        <tr>
            <td style="border-bottom: 3px double #000000; border-left: 3px double #000000" height="24"
                align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 3px double #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 3px double #000000; border-left: 1px solid #000000; border-right: 3px double #000000"
                align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000" height="20" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000; border-left: 1px solid #000000" height="20" align="left"
                valign=middle>
                <font face="Verdana"> In Words :</font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" height="26"
                align="left" valign=middle>
                <b>
                    <font face="Verdana"> ---</font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
        </tr>
        <tr>
            <td height="20" align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)">
                <b>
                    <font face="Verdana" size=4><br></font>
                </b>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)"><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle><b>
                    <font face="Verdana">Note :</font>
                </b></td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle>
                <font face="Verdana">1. Pembayaran transfer, Cheque / Bilyet Giro ke : </font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle><b>
                    <font face="Verdana"> BCA Cabang BSD</font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle><b>
                    <font face="Verdana"> A/n </font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana">:</font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana">TJHIN FAISAL WIJAYA</font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana"><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle><b>
                    <font face="Verdana"> Acc No </font>
                </b></td>
            <td align="left" valign=middle><b>
                    <font face="Verdana">:</font>
                </b></td>
            <td colspan=3 align="left" valign=middle sdnum="1033;0;#,##0"><b>
                    <font face="Verdana">4979 2000 11</font>
                </b></td>
            <td align="left" valign=middle sdnum="1033;0;#,##0"><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle sdnum="1033;0;#,##0"><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle sdnum="1033;0;#,##0"><b>
                    <font face="Verdana" size=4><br></font>
                </b></td>
            <td align="left" valign=middle
                sdnum="1033;0;_([$Rp-421]* #,##0_);_([$Rp-421]* \(#,##0\);_([$Rp-421]* &quot;-&quot;??_);_(@_)">
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="19" align="left" valign=middle>
                <font face="Verdana">2. Pembayaran dinyatakan lunas apabila dana sudah cair / efektif dalam rekening
                    kami.</font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="25" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td rowspan=6 align="left" valign=middle>
                <font face="Times New Roman" size=4><br>
                </font>
            </td>
        </tr>
        <tr>
            <td height="26" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="25" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="25" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="11" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
        </tr>
        <tr>
            <td height="25" align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>
            <td align="left" valign=middle>
                <font face="Verdana" size=4><br></font>
            </td>

            {{-- <img src="{{ asset('/img/picture2.png') }}" width=49 height=116 hspace=56 vspace=13> --}}
            {{-- <img src="/.../sites/docker/production/lelang-ikan/public/img/picture2.png" width=49 height=116 hspace=56
                vspace=13> --}}
        </tr>
    </table>
@endforeach
