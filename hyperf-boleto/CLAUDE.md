# Projeto 02 — Boleto Mini (o carro-chefe)

## Para o Claude Code: contexto deste projeto

Este é **o projeto mais importante da série**. Simula 1:1 o time real da vaga PHP do PicPay: **Pagamento de Boletos e DDA**.

Um scaffold inicial já existe (em `hyperf-boleto-mini.zip` do pacote de estudos). As TASKs daqui partem dele e evoluem até um projeto sênior-friendly.

**Objetivo pedagógico:** ao final, João Pedro consegue defender na entrevista **por que cada decisão técnica foi tomada** — nada é copiado sem entender.

## Tópicos da vaga exercitados (praticamente todos)

- PHP 8+ moderno
- Hyperf + Swoole
- MySQL (transações, locks, índices)
- Redis (idempotência, cache)
- RabbitMQ (outbox publisher + retry + DLX)
- Docker
- TDD em múltiplos níveis
- Qualidade de código / design patterns (**Outbox, State Machine, Value Object, Service Layer, Circuit Breaker, Retry**)
- Microsserviços horizontalmente escaláveis
- Jobs de alto desempenho
- OWASP (idempotência, HMAC webhook, validação)
- Clean Code + SOLID + DRY

## Princípios não-negociáveis

1. **Idempotência em TUDO**. Qualquer retry deve ser seguro. Mesma `Idempotency-Key` → mesma resposta, sem criar recurso duplicado.
2. **Transações explicitam a consistência**. Operação multi-tabela **sempre** dentro de `Db::transaction()`. Aqui moram bugs caríssimos em pagamento.
3. **Outbox antes de broker**. Nunca publique no RabbitMQ direto em fluxo síncrono — sempre via tabela `outbox_events` + worker.
4. **State machine centralizada**. A máquina de estados do pagamento vive em **uma** classe. Se outra parte do código decide transição, é bug.
5. **Typed exceptions**. `throw new \Exception("algo errado")` é proibido. Sempre exceção específica.
6. **Observabilidade mínima em código novo**. Log estruturado com `request_id`, `payment_id`, `status`. Sem isso, debug em prod é impossível.
7. **Nunca float pra dinheiro**. Sempre int em centavos ou `Money` VO.

## Convenções específicas do domínio

- **PaymentStatus**: `PENDING`, `AUTHORIZED`, `SETTLED`, `FAILED`, `REVERSED`. Transições documentadas em `PaymentStateMachine`.
- **Idempotency-Key**: header obrigatório em `POST /payments`. Sem key, 400.
- **Webhook do banco** vem via `POST /webhooks/bank-callback`. Deve ser **idempotente** e validado por HMAC.
- **Money** sempre como VO. Persistência como `bigint unsigned` (cents).
- **UUIDv4 ou v7** como PK — escolha documentada no README.

## TASKs deste projeto

| # | Título | Tempo |
|---|--------|-------|
| 01 | Setup Docker stack completa | 1h |
| 02 | Schema do domínio + migrations | 1h |
| 03 | Value Objects + Enums (TDD) | 2h |
| 04 | State Machine do Payment (TDD) | 2h |
| 05 | Model + Repository | 1h |
| 06 | Service Layer + primeiro endpoint POST /payments | 2h |
| 07 | Transactional Outbox + worker | 3h |
| 08 | Idempotency Middleware (Redis) | 3h |
| 09 | Exception handler + typed exceptions | 1h |
| 10 | Webhook do banco + HMAC | 2h |
| 11 | Retry + Circuit Breaker nas chamadas ao banco | 3h |
| 12 | OutboxPublisher com FOR UPDATE SKIP LOCKED | 2h |
| 13 | Observabilidade: logs estruturados + métricas Prometheus + traces | 4h (ir além) |
| 14 | Graceful shutdown + stress test | 2h |
| 15 | OWASP checklist + validações de entrada | 2h |
| 16 | README definitivo + ADRs (decisões arquiteturais) | 2h |

**Total:** ~30h no papel, 1,5x na prática.

## Integração com o scaffold existente

O scaffold (`hyperf-boleto-mini.zip`) já tem: docker-compose, Dockerfile, composer.json, configs, classes de domínio básicas, controllers, middlewares de idempotência e request-id skeleton, service, models, job com breaker, exception handler, command do outbox, migrations, testes iniciais, README.

As TASKs **não rebootam** o scaffold — elas **expandem e endurecem**. Cada TASK pode pedir pra você:
- Ler o arquivo X do scaffold
- Criar testes que ele ainda não tem
- Refatorar pontos frágeis
- Completar features marcadas como `TODO(você)`

## Como você (Claude Code) deve se portar

- **Leia o scaffold antes** de codar qualquer coisa nova. Tem decisões tomadas lá; não contrarie sem justificar.
- **Teste primeiro** nas TASKs que exigem TDD (quase todas).
- **Refatore gradual**: mudanças grandes em várias partes merecem múltiplos commits.
- **Mostre diff** dos arquivos que tocou, não re-escreva arquivos inteiros sem necessidade.
- **Se identificar inconsistência** entre scaffold e TASK, **pergunte** — não assuma.
- **Não economize em testes** nesta suite. É aqui que a vaga pega.