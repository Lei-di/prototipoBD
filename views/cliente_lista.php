<?php
// views/cliente_lista.php
?>

<style>
    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        text-align: center;
        min-width: 80px;
        
        text-decoration: none; 
    }
    .status-ativo { background-color: #28a745; } 
    .status-a-vencer { background-color: #ffc107; color: #333; } 
    .status-encerrado { background-color: #dc3545; } 
    .status-nenhum { background-color: #6c757d; } 
</style>

<h2>Clientes Cadastrados</h2>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f4f4f4;">
            <th style="padding: 8px;">CPF</th>
            <th style="padding: 8px;">Nome</th>
            <th style="padding: 8px;">Email</th>
            <th style="padding: 8px;">Telefone</th>
            <th style="padding: 8px;">Contratos</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($clientes && count($clientes) > 0) {
            
            $hoje = new DateTime();
            $limite_vencer = new DateTime('+30 days');

            foreach ($clientes as $cliente) {
                
                $status_classe = 'status-nenhum';
                $status_texto = 'Sem Contrato';
                
                $datas_html = '';

                if (!empty($cliente['data_inicio'])) {
                    
                    $data_inicio = new DateTime($cliente['data_inicio']);
                    $data_fim = new DateTime($cliente['data_fim']);
                    
                    $data_inicio_fmt = $data_inicio->format('d/m/Y');
                    $data_fim_fmt = $data_fim->format('d/m/Y');
                    
                    $datas_html = "<br><small style='font-size: 0.9em; color: #333;'>
                                     Início: $data_inicio_fmt<br>
                                     Fim: $data_fim_fmt
                                   </small>";

                    if ($hoje > $data_fim) {
                        $status_classe = 'status-encerrado';
                        $status_texto = 'Encerrado';
                    } elseif ($limite_vencer >= $data_fim) {
                        $status_classe = 'status-a-vencer';
                        $status_texto = 'A Vencer';
                    } else {
                        $status_classe = 'status-ativo';
                        $status_texto = 'Ativo';
                    }
                }

                echo "<tr>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['cpf']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['nome_cliente']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['email']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['telefone']) . "</td>";
                
                echo "<td style='padding: 8px; text-align: center; line-height: 1.5;'>";
                
                echo "<span class='status-badge $status_classe'>$status_texto</span>";
      
                echo $datas_html;
                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='padding: 8px; text-align: center;'>Nenhum cliente cadastrado.</td></tr>";
        }
        ?>
    </tbody>
</table>