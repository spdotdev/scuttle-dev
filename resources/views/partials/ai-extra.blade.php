{{-- Extra blocks on the AI Integration page: the workflow, which model for what, the words, the models in use, a thin timeline. --}}
<section class="s tight"><div class="wrap">
  <div class="plate rv flow">
    <div class="bh"><span class="lbl dot">How an automation is built</span><h2>The workflow, step by step</h2><p>Every automation I build has the same shape, whether it runs as a Claude agent, as n8n flows or as a queue job in your application. The value sits in the middle: your knowledge and your tools, connected safely.</p></div>
    <ol class="steps-flow">
      <li><i class="lt"></i><b>Trigger</b><span>A mail arrives, a ticket opens, a webhook fires, a clock ticks. n8n, a Laravel job or a cron.</span></li>
      <li><i class="lt"></i><b>Classify</b><span>A small, fast model decides what this is and whether it is in scope. Cheap, measured on the evaluation set.</span></li>
      <li><i class="lt"></i><b>Retrieve</b><span>RAG: the relevant pages, tickets or rows from your own data, with sources. Embeddings built locally when the data may not leave.</span></li>
      <li><i class="lt"></i><b>Act with tools</b><span>The agent calls named tools through MCP: your API, your CRM, your accounting. Read by default, write only where agreed.</span></li>
      <li><i class="lt"></i><b>Check</b><span>Rules it must pass, and a person where the rules say so. Nothing leaves the building without that step.</span></li>
      <li><i class="lt"></i><b>Log and report</b><span>Every call audited, cost counted against the cap, a summary to the people who care.</span></li>
    </ol>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv">
    <div class="bh"><span class="lbl dot">Which model for what</span><p>Picked per step, by job. The cheapest that passes the evaluation set wins; the expensive ones are for judgment, not for sorting mail.</p></div>
    <div class="mtable">
      <table>
        <thead><tr><th>Job</th><th>What I reach for</th><th>Why</th></tr></thead>
        <tbody>
          <tr><td>Sorting, routing, extraction at volume</td><td>Claude Haiku 5.5, or a local small model</td><td>Fast and cheap; the evaluation set shows when it is good enough.</td></tr>
          <tr><td>Drafting, summarizing, answering from your documents</td><td>Claude Sonnet 5.5</td><td>The balance of quality and cost for most daily work.</td></tr>
          <tr><td>Long multi-step agent work, code, hard judgment</td><td>Claude Opus 5.5 or Fable 5.1</td><td>When a wrong step costs more than the tokens.</td></tr>
          <tr><td>Always-on listening, offline, on a device</td><td>A small model trained for that one job</td><td>Privacy and cost: nothing leaves the device, nothing is billed per hour.</td></tr>
          <tr><td>Speech to text</td><td>Whisper, local or hosted</td><td>Good enough for most audio; local when the recordings are sensitive.</td></tr>
          <tr><td>Data that may not leave your servers</td><td>Local models through Ollama, local embeddings</td><td>Slower and less capable, and the only honest choice for that data.</td></tr>
          <tr><td>Glueing services without code</td><td>n8n flows</td><td>Fine for simple triggers and hand-offs; code when logic, tests and retries matter.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv ai-words">
    <div class="bh"><span class="lbl dot">The words, in plain language</span><h2>What the terms mean, and where the value sits</h2><p>The value is not in the model. It is in connecting the model to your own knowledge and your own tools, safely. These are the parts I build with.</p></div>
    <dl class="gloss">
      <div><dt>Agent</dt><dd>A model that works in steps with tools: reads the inbox, calls your API, writes the result, asks a person when unsure.</dd></div>
      <div><dt>Skill</dt><dd>A written procedure the agent follows for one job, with the checks it must run. Your way of working, made repeatable.</dd></div>
      <div><dt>MCP</dt><dd>Model Context Protocol, the open standard that lets any assistant call named tools on your systems. One gateway, every assistant.</dd></div>
      <div><dt>RAG</dt><dd>Retrieval over your own documents, tickets or database, so answers come with their source instead of from memory.</dd></div>
      <div><dt>Embeddings</dt><dd>Numbers that make text searchable by meaning. The index behind retrieval; built locally when the data may not leave.</dd></div>
      <div><dt>Evaluation set</dt><dd>Real examples with known answers. The system ships when it passes them, and every change is measured against them.</dd></div>
      <div><dt>Fine-tuning and small models</dt><dd>Training a small model for one narrow job that must run fast, offline or cheap. I trained a wake-word model for my own voice assistant this way.</dd></div>
      <div><dt>Guardrails</dt><dd>What the agent may never do, what needs a human, what is logged, and how much it may spend per month.</dd></div>
    </dl>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv belts thin">
    <div class="bh"><span class="lbl dot">Models and tools in use</span><p>Picked per job: the best model for the step, the cheapest that passes the evaluation set, local when the data may not leave.</p></div>
    <div class="belt" aria-label="Models"><div class="track">
      @php($models = ['Claude Fable 5.1', 'Claude Opus 5.5', 'Claude Sonnet 5.5', 'Claude Haiku 5.5', 'GPT', 'Gemini', 'Llama', 'Mistral', 'Qwen', 'Whisper', 'Local models via Ollama', 'YOLO (vision)'])
      @for($r = 0; $r < 2; $r++)@foreach($models as $m)<span class="tk ai"><i></i>{{ $m }}</span>@endforeach @endfor
    </div></div>
    <div class="belt rev" aria-label="Tools"><div class="track">
      @php($tools = ['Claude Code', 'Claude API', 'MCP servers', 'Custom skills', 'Agent SDK', 'n8n', 'Playwright (browser as hands)', 'LanceDB', 'SQLite as memory', 'Python', 'Laravel queues', 'Docker', 'Ollama'])
      @for($r = 0; $r < 2; $r++)@foreach($tools as $t)<span class="tk mono"><i></i>{{ $t }}</span>@endforeach @endfor
    </div></div>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv tline">
    <div class="bh"><span class="lbl dot">How fast this moves</span><p>Claude models since 2024. Each step changed what an agent can be trusted with; the build has to be able to swap the model without a rewrite.</p></div>
    <div class="tl2">
      <div class="axis"><span>2024</span><span>2025</span><span>2026</span></div>
      <div class="row"><b>Claude 3</b><span>Mar 2024</span><i style="--x:4%"></i></div>
      <div class="row"><b>3.5 Sonnet</b><span>Jun 2024</span><i style="--x:14%"></i></div>
      <div class="row"><b>3.5 Haiku</b><span>Oct 2024</span><i style="--x:26%"></i></div>
      <div class="row"><b>3.7 Sonnet</b><span>Feb 2025</span><i style="--x:37%"></i></div>
      <div class="row"><b>Claude 4</b><span>May 2025</span><i style="--x:45%"></i></div>
      <div class="row"><b>Sonnet 4.5 · Haiku 4.5 · Opus 4.5</b><span>Sep to Nov 2025</span><i style="--x:60%"></i></div>
      <div class="row"><b>Claude 5 family, Fable 5.1</b><span>2026</span><i style="--x:78%"></i></div>
      <div class="row"><b>Opus 5.5 · Sonnet 5.5 · Haiku 5.5</b><span>Sep to Oct 2026</span><i style="--x:92%"></i></div>
    </div>
    <small class="bn">Dates from Anthropic's model list and release notes. The point is the pace, not the detail.</small>
  </div>
</div></section>
