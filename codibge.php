<form method="post">
	<textarea name="lista"></textarea>
	<button type="submit">Vai</button>
</form>

<?php

class JulianaAintablian
{
	public function getConnection($municipio)
	{
		$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/$municipio",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPGET => TRUE,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPGET => TRUE,
			)
		);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if (!empty($err)) {
			return $err;
		}
		curl_close($curl);

		return json_decode($response);
	}

	public function buscarIBGE($municipios)
	{
		foreach ($municipios as $municipio) {
			$retorno[] = $this->getConnection(str_replace(' ', '-', $municipio));
		}
		return $retorno;
	}
}
if (isset($_POST['lista'])) {
	$a = new JulianaAintablian();
	$lista = explode('\n',$_POST['lista']);
	$cidades = $a->buscarIBGE($lista);

	echo "<tr style='margin-top:50px; margin-bottom:50px'>";
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Cidade</th>";
	echo "<th>UF</th>";
	echo "<th>Codigo IBGE</th>";
	echo "</tr>";
	foreach ($cidades as $cidade) {
		echo "<tr>";
		echo "<td>$cidade->nome</td>";
		echo "<td>" . $cidade->microrregiao->mesorregiao->UF->nome . "</td>";
		echo "<td>$cidade->id</td>";
		echo "</tr>";
	}
	echo "</table>";
}
