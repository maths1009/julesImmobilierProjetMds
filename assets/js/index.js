import { charts } from "./charts.js";
import { getMeets } from "./api.js";

const data = await getMeets();

charts(data);
