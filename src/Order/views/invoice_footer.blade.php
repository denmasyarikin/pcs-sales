<table class="footer" width="100%">
    <tr>
      <td align="center" width="30%">
        Penerima<br><br>
        @if ($order->customer)
            <b>
                @if ($order->chanel->type === 'company')
                    ({{$order->customer->contact_person}})
                @else
                    ({{$order->customer->name}})
                @endif
            </b>
        @else
            -
        @endif
      </td>
      <td align="center">
        <b>Terima Kasih</b>
      </td>
      <td align="center" width="30%">
        Customer Service<br><br>
        <b>({{$order->cs_name}})</b>
      </td>
    </tr>
</table>