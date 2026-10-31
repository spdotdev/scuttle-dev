{{-- Extra blocks on the AI Agents and Skills page: the workflow, which model for what, the words, the models in use, a thin timeline. --}}
<section class="s tight"><div class="wrap">
  <div class="plate rv flow">
    <div class="bh"><span class="lbl dot" data-i="ax_0">How an automation is built</span><h2 data-i="ax_5">The workflow, step by step</h2><p data-i="ax_7">Every automation I build has the same shape, whether it runs as a Claude agent, as n8n flows or as a queue job in your application. The value sits in the middle: your knowledge and your tools, connected safely.</p></div>
    <ol class="steps-flow">
      <li><i class="lt"></i><b data-i="ax_12">Trigger</b><span data-i="ax_26">A mail arrives, a ticket opens, a webhook fires, a clock ticks. n8n, a Laravel job or a cron.</span></li>
      <li><i class="lt"></i><b data-i="ax_13">Classify</b><span data-i="ax_27">A small, fast model decides what this is and whether it is in scope. Cheap, measured on the evaluation set.</span></li>
      <li><i class="lt"></i><b data-i="ax_14">Retrieve</b><span data-i="ax_28">RAG: the relevant pages, tickets or rows from your own data, with sources. Embeddings built locally when the data may not leave.</span></li>
      <li><i class="lt"></i><b data-i="ax_15">Act with tools</b><span data-i="ax_29">The agent calls named tools through MCP: your API, your CRM, your accounting. Read by default, write only where agreed.</span></li>
      <li><i class="lt"></i><b data-i="ax_16">Check</b><span data-i="ax_30">Rules it must pass, and a person where the rules say so. Nothing leaves the building without that step.</span></li>
      <li><i class="lt"></i><b data-i="ax_17">Log and report</b><span data-i="ax_31">Every call audited, cost counted against the cap, a summary to the people who care.</span></li>
    </ol>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv">
    <div class="bh"><span class="lbl dot" data-i="ax_1">Which model for what</span><p data-i="ax_8">Picked per step, by job. The cheapest that passes the evaluation set wins; the expensive ones are for judgment, not for sorting mail.</p></div>
    <div class="mtable" tabindex="0" role="region" aria-label="Which model for what">
      <table>
        <thead><tr><th data-i="ax_32">Job</th><th data-i="ax_33">What I reach for</th><th data-i="ax_34">Why</th></tr></thead>
        <tbody>
          <tr><td data-i="ax_35">Sorting, routing, extraction at volume</td><td data-i="ax_36">Claude Haiku 5.5, or a local small model</td><td data-i="ax_37">Fast and cheap; the evaluation set shows when it is good enough.</td></tr>
          <tr><td data-i="ax_38">Drafting, summarizing, answering from your documents</td><td data-i="ax_39">Claude Sonnet 5.5</td><td data-i="ax_40">The balance of quality and cost for most daily work.</td></tr>
          <tr><td data-i="ax_41">Long multi-step agent work, code, hard judgment</td><td data-i="ax_42">Claude Opus 5.5 or Fable 5.1</td><td data-i="ax_43">When a wrong step costs more than the tokens.</td></tr>
          <tr><td data-i="ax_44">Always-on listening, offline, on a device</td><td data-i="ax_45">A small model trained for that one job</td><td data-i="ax_46">Privacy and cost: nothing leaves the device, nothing is billed per hour.</td></tr>
          <tr><td data-i="ax_47">Speech to text</td><td data-i="ax_48">Whisper, local or hosted</td><td data-i="ax_49">Good enough for most audio; local when the recordings are sensitive.</td></tr>
          <tr><td data-i="ax_50">Data that may not leave your servers</td><td data-i="ax_51">Local models through Ollama, local embeddings</td><td data-i="ax_52">Slower and less capable, and the only honest choice for that data.</td></tr>
          <tr><td data-i="ax_53">Glueing services without code</td><td data-i="ax_54">n8n flows</td><td data-i="ax_55">Fine for simple triggers and hand-offs; code when logic, tests and retries matter.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv ai-words">
    <div class="bh"><span class="lbl dot" data-i="ax_2">The words, in plain language</span><h2 data-i="ax_6">What the terms mean, and where the value sits</h2><p data-i="ax_9">The value is not in the model. It is in connecting the model to your own knowledge and your own tools, safely. These are the parts I build with.</p></div>
    <dl class="gloss">
      <div><dt data-i="ax_56">Agent</dt><dd data-i="ax_64">A model that works in steps with tools: reads the inbox, calls your API, writes the result, asks a person when unsure.</dd></div>
      <div><dt data-i="ax_57">Skill</dt><dd data-i="ax_65">A written procedure the agent follows for one job, with the checks it must run. Your way of working, made repeatable.</dd></div>
      <div><dt data-i="ax_58">MCP</dt><dd data-i="ax_66">Model Context Protocol, the open standard that lets any assistant call named tools on your systems. One gateway, every assistant.</dd></div>
      <div><dt data-i="ax_59">RAG</dt><dd data-i="ax_67">Retrieval over your own documents, tickets or database, so answers come with their source instead of from memory.</dd></div>
      <div><dt data-i="ax_60">Embeddings</dt><dd data-i="ax_68">Numbers that make text searchable by meaning. The index behind retrieval; built locally when the data may not leave.</dd></div>
      <div><dt data-i="ax_61">Evaluation set</dt><dd data-i="ax_69">Real examples with known answers. The system ships when it passes them, and every change is measured against them.</dd></div>
      <div><dt data-i="ax_62">Fine-tuning and small models</dt><dd data-i="ax_70">Training a small model for one narrow job that must run fast, offline or cheap. I trained a wake-word model for my own voice assistant this way.</dd></div>
      <div><dt data-i="ax_63">Guardrails</dt><dd data-i="ax_71">What the agent may never do, what needs a human, what is logged, and how much it may spend per month.</dd></div>
    </dl>
  </div>
</div></section>

<section class="s tight"><div class="wrap">
  <div class="plate rv belts thin">
    <div class="bh"><span class="lbl dot" data-i="ax_3">Models and tools in use</span><p data-i="ax_10">Picked per job: the best model for the step, the cheapest that passes the evaluation set, local when the data may not leave.</p></div>
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
    <div class="bh"><span class="lbl dot" data-i="ax_4">How fast this moves</span><p data-i="ax_11">Claude models since 2024. Each step changed what an agent can be trusted with; the build has to be able to swap the model without a rewrite.</p></div>
    <div class="tl2">
      <div class="axis"><span>2024</span><span>2025</span><span>2026</span></div>
      <div class="row"><b data-i="ax_18">Claude 3</b><span data-i="ax_73">Mar 2024</span><i style="--x:4%"></i></div>
      <div class="row"><b data-i="ax_19">3.5 Sonnet</b><span data-i="ax_74">Jun 2024</span><i style="--x:14%"></i></div>
      <div class="row"><b data-i="ax_20">3.5 Haiku</b><span data-i="ax_75">Oct 2024</span><i style="--x:26%"></i></div>
      <div class="row"><b data-i="ax_21">3.7 Sonnet</b><span data-i="ax_76">Feb 2025</span><i style="--x:37%"></i></div>
      <div class="row"><b data-i="ax_22">Claude 4</b><span data-i="ax_77">May 2025</span><i style="--x:45%"></i></div>
      <div class="row"><b data-i="ax_23">Sonnet 4.5 · Haiku 4.5 · Opus 4.5</b><span data-i="ax_78">Sep to Nov 2025</span><i style="--x:60%"></i></div>
      <div class="row"><b data-i="ax_24">Claude 5 family, Fable 5.1</b><span>2026</span><i style="--x:78%"></i></div>
      <div class="row"><b data-i="ax_25">Opus 5.5 · Sonnet 5.5 · Haiku 5.5</b><span data-i="ax_79">Sep to Oct 2026</span><i style="--x:92%"></i></div>
    </div>
    <small class="bn" data-i="ax_72">Dates from Anthropic's model list and release notes. The point is the pace, not the detail.</small>
  </div>
</div></section>

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
  ax_0: "Hoe een automatisering wordt gebouwd",
  ax_1: "Welk model waarvoor",
  ax_2: "De woorden, in gewone taal",
  ax_3: "Modellen en tools in gebruik",
  ax_4: "Hoe snel dit gaat",
  ax_5: "De workflow, stap voor stap",
  ax_6: "Wat de termen betekenen, en waar de waarde zit",
  ax_7: "Elke automatisering die ik bouw heeft dezelfde vorm, of hij nu draait als Claude-agent, als n8n-flows of als queue-job in je applicatie. De waarde zit in het midden: jouw kennis en jouw tools, veilig verbonden.",
  ax_8: "Per stap gekozen, per taak. Het goedkoopste model dat de evaluatieset haalt wint; de dure zijn voor beoordeling, niet voor het sorteren van mail.",
  ax_9: "De waarde zit niet in het model. Ze zit in het veilig verbinden van het model met je eigen kennis en je eigen tools. Dit zijn de onderdelen waarmee ik bouw.",
  ax_10: "Per taak gekozen: het beste model voor de stap, het goedkoopste dat de evaluatieset haalt, lokaal als de data niet weg mag.",
  ax_11: "Claude-modellen sinds 2024. Elke stap veranderde waaraan je een agent kunt toevertrouwen; de bouw moet het model kunnen wisselen zonder rewrite.",
  ax_12: "Trigger",
  ax_13: "Classificeren",
  ax_14: "Ophalen",
  ax_15: "Handelen met tools",
  ax_16: "Controleren",
  ax_17: "Loggen en rapporteren",
  ax_18: "Claude 3",
  ax_19: "3.5 Sonnet",
  ax_20: "3.5 Haiku",
  ax_21: "3.7 Sonnet",
  ax_22: "Claude 4",
  ax_23: "Sonnet 4.5 · Haiku 4.5 · Opus 4.5",
  ax_24: "Claude 5-familie, Fable 5.1",
  ax_25: "Opus 5.5 · Sonnet 5.5 · Haiku 5.5",
  ax_26: "Er komt een mail binnen, een ticket gaat open, een webhook vuurt, een klok tikt. n8n, een Laravel-job of een cron.",
  ax_27: "Een klein, snel model bepaalt wat dit is en of het binnen de scope valt. Goedkoop, gemeten op de evaluatieset.",
  ax_28: "RAG: de relevante pagina's, tickets of rijen uit je eigen data, met bronnen. Embeddings lokaal gebouwd als de data niet weg mag.",
  ax_29: "De agent roept benoemde tools aan via MCP: je API, je CRM, je boekhouding. Standaard lezen, alleen schrijven waar afgesproken.",
  ax_30: "Regels die het moet halen, en een mens waar de regels dat zeggen. Zonder die stap verlaat niets het pand.",
  ax_31: "Elke aanroep geaudit, kosten geteld tegen het plafond, een samenvatting voor wie het aangaat.",
  ax_32: "Taak",
  ax_33: "Waar ik naar grijp",
  ax_34: "Waarom",
  ax_35: "Sorteren, routeren, extraheren op volume",
  ax_36: "Claude Haiku 5.5, of een lokaal klein model",
  ax_37: "Snel en goedkoop; de evaluatieset laat zien wanneer het goed genoeg is.",
  ax_38: "Opstellen, samenvatten, antwoorden uit je documenten",
  ax_39: "Claude Sonnet 5.5",
  ax_40: "De balans tussen kwaliteit en kosten voor het meeste dagelijkse werk.",
  ax_41: "Lang agentwerk in meerdere stappen, code, zware beoordeling",
  ax_42: "Claude Opus 5.5 of Fable 5.1",
  ax_43: "Als een verkeerde stap meer kost dan de tokens.",
  ax_44: "Altijd luisteren, offline, op een apparaat",
  ax_45: "Een klein model getraind voor die ene taak",
  ax_46: "Privacy en kosten: niets verlaat het apparaat, niets wordt per uur gefactureerd.",
  ax_47: "Spraak naar tekst",
  ax_48: "Whisper, lokaal of gehost",
  ax_49: "Goed genoeg voor de meeste audio; lokaal als de opnames gevoelig zijn.",
  ax_50: "Data die je servers niet mag verlaten",
  ax_51: "Lokale modellen via Ollama, lokale embeddings",
  ax_52: "Langzamer en minder krachtig, en de enige eerlijke keuze voor die data.",
  ax_53: "Diensten aan elkaar plakken zonder code",
  ax_54: "n8n-flows",
  ax_55: "Prima voor simpele triggers en overdrachten; code als logica, tests en retries ertoe doen.",
  ax_56: "Agent",
  ax_57: "Skill",
  ax_58: "MCP",
  ax_59: "RAG",
  ax_60: "Embeddings",
  ax_61: "Evaluatieset",
  ax_62: "Finetunen en kleine modellen",
  ax_63: "Vangrails",
  ax_64: "Een model dat in stappen met tools werkt: leest de inbox, roept je API aan, schrijft het resultaat, vraagt een mens als het twijfelt.",
  ax_65: "Een geschreven procedure die de agent volgt voor één taak, met de controles die hij moet uitvoeren. Jouw werkwijze, herhaalbaar gemaakt.",
  ax_66: "Model Context Protocol, de open standaard waarmee elke assistent benoemde tools op je systemen kan aanroepen. Eén poort, elke assistent.",
  ax_67: "Retrieval over je eigen documenten, tickets of database, zodat antwoorden met hun bron komen in plaats van uit het geheugen.",
  ax_68: "Getallen die tekst doorzoekbaar maken op betekenis. De index achter retrieval; lokaal gebouwd als de data niet weg mag.",
  ax_69: "Echte voorbeelden met bekende antwoorden. Het systeem gaat live als het ze haalt, en elke wijziging wordt ertegen gemeten.",
  ax_70: "Een klein model trainen voor één smalle taak die snel, offline of goedkoop moet draaien. Zo heb ik een wake-wordmodel getraind voor mijn eigen spraakassistent.",
  ax_71: "Wat de agent nooit mag doen, wat een mens nodig heeft, wat gelogd wordt en hoeveel hij per maand mag uitgeven.",
  ax_72: "Data uit de modellijst en release notes van Anthropic. Het gaat om het tempo, niet om het detail.",
  ax_73: "mrt 2024",
  ax_74: "jun 2024",
  ax_75: "okt 2024",
  ax_76: "feb 2025",
  ax_77: "mei 2025",
  ax_78: "sep tot nov 2025",
  ax_79: "sep tot okt 2026"
});
</script>
@endpush
