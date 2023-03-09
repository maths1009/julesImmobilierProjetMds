import { chartsAgent } from "./charts.js";
import { getMeetsAgents } from "./api.js";

const data = await getMeetsAgents();

await chartsAgent(data);
