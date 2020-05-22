@if($type === 'success')
Pagamento efetuado com sucesso.
@elseif($type === 'pending')
Recebemos sua solicitação. Seu pagamento ainda está em análise.
@elseif($type === 'failure')
Não foi possível processar seu pagamento.
@endif
