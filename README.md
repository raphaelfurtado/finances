# Minhas Finanças

**Suas contas fixas, sob controle.** E um consultor que não passa pano.

Minhas Finanças é um app web para quem quer parar de ser surpreendido pelo boleto de todo mês. O foco não é ser mais uma planilha bonita: é cuidar das **despesas fixas e recorrentes** (aluguel, assinaturas, parcelas, contas de consumo) e mostrar com antecedência para onde o seu dinheiro já está comprometido.

E tudo isso com o **Consultor Sincero**, que fala com você do jeito que um amigo honesto falaria. Sem sync bancário que toda hora quebra, sem linguagem de banco.

---

## O que ele faz

- **Gastos fixos**: cadastre o que se repete todo mês e receba lembrete antes do vencimento.
- **Contas do mês**: veja o que já foi pago e o que ainda vai cair.
- **Transações**: lançamento manual de entradas e saídas, com categorias.
- **Importação de extrato**: arquivos OFX e CSV do seu banco.
- **Orçamentos**: limite por categoria, para saber quando você estourou (e o consultor vai avisar).
- **Empréstimos e crédito**: controle de empréstimos com parcelas e acompanhamento do que falta pagar.
- **Dashboard**: saldo, projeção e gráficos do mês.
- **Notificações push**: lembretes no navegador/celular (Web Push).
- **Plano Premium**: relatórios e planilhas avançadas, com assinatura via Stripe.
- **Painel admin**: usuários, planos, depoimentos, campanhas de push, monitoramento e logs do cron.

## Stack

| Camada | Tecnologia |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Livewire 3, Blade, Tailwind CSS 4 |
| Gráficos e ícones | Chart.js, Lucide (servidos localmente, sem CDN) |
| Login | Google OAuth (Laravel Socialite) |
| Pagamentos | Stripe (Laravel Cashier) |
| Notificações | Web Push (`laravel-notification-channels/webpush`) |
| Importação | OFX (`cihansenturk/ofxparser`), CSV, Excel |
| Monitoramento | Laravel Pulse |
| Banco | SQLite no desenvolvimento, MySQL em produção |
