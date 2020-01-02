<%
    let invoice = model.invoice;
    let lines = model.lines;
    let account = model.account;
%>

<!DOCTYPE html>

<meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self'; media-src 'self' data: blob:; img-src 'self' ; script-src 'self'; connect-src 'self'">
<meta charset="UTF-8">
<link rel='stylesheet' href='/static/amura/billing/admin/main.css' />

<title>Factura <%= invoice.number %></title>

<div class="amura_invoice">

	<div>
		<table style="border: 1px black solid;" width="100%">
			<td width="50%" height="50px" style="border: 1px red solid;"></td>
			<td width="50%">Datos empresa</td>
		</table>
	</div>

    <div class="info">l.jkhkljhlkjhljkh
        <div class="number">
            Nº Factura: <%= invoice.number %>
            <% if(invoice.cancelationNumber) { %>
                <br>
                Rectifica la factura: <%= invoice.cancelationNumber %>
            <% } %>
        </div>
        <div class="date">
            Fecha: <%= invoice.date.local().format("d") %>
        </div>
    </div>

    <div class="header">
        <div class="company">
            Empresa: <%= invoice.companyName %><br>
            CIF: <%= invoice.companyCIF %><br>
            Calle: <%= invoice.companyStreet %><br>
            Población: <%= invoice.companyTown %><br>
            Provincia: <%= invoice.companyProvince %><br>
            Código Postal: <%= invoice.companyzipCode %><br>
        </div>

        <div class="client">
            Nombre: <%= invoice.clientName %><br>
            CIF: <%= invoice.clientNationalID %><br>
            Calle: <%= invoice.clientStreet %><br>
            Población: <%= invoice.clientTown %><br>
            Provincia: <%= invoice.clientProvince %><br>
            Código Postal: <%= invoice.clientzipCode %><br>
            País: <%= invoice.clientCountry %><br>
        </div>
    </div>

    <table class="items">
        <tr>
            <th>Concepto</th>
            <th>Cantidad</th>
            <th>Descuento</th>
            <th>Total</th>
        </tr>
        <% for(let line of lines) { %>
            <tr>
                <td><%= line.description || line.name %></td>
                <td><%= i18n.format("i", line.quantity) %></td>
                <td><%= i18n.format("i", line.discount) %></td>
                <td><%= i18n.format("c", line.total) %></td>
            </tr>
        <% } %>
    </table>

    <table class="summary">
        <tr>
            <td>Base imponible</td>
            <td><%= i18n.format("c", model.net) %></td>
        </tr>
        <% for(let tax of model.taxes) { %>
            <tr>
                <td><%= tax.name %></td>
                <td><%= i18n.format("c", tax.total) %></td>
            </tr>
        <% } %>
        <tr>
            <td>Total</td>
            <td><%= i18n.format("c", model.total) %></td>
        </tr>
    </table>

    <div class="comments">
        <%= invoice.comments %>
    </div>
</div>