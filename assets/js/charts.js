import { getAgents } from "./api.js";
import { color } from "./constant.js";
import { countByDayOfWeek } from "./snippets.js";

/**
 * It takes an object as an argument, and returns a chart
 * @param data - the data we want to display in the chart
 */
export function charts(data) {
  const ctx = document.getElementById("myEvolution").getContext("2d");

  const myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: Object.keys(data.currentWeek),
      datasets: [
        {
          pointRadius: 0,
          lineTension: 0.3,
          borderColor: "#ffc72c",
          label: "Cette semaine",
          data: Object.values(data.currentWeek),
        },
        {
          pointRadius: 0,
          lineTension: 0.3,
          borderWidth: 1,
          borderColor: "#004f71",
          label: "Semaine dernière",
          data: Object.values(data.previousWeek),
        },
      ],
    },
    options: {
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          ticks: {
            stepSize: 1,
          },
        },
      },
      plugins: {
        legend: {
          display: true,
        },
      },
    },
  });
}

/**
 * It takes an array of arrays of objects, and returns a chart
 * @param data - the data to be displayed in the chart
 */
export async function chartsAgent(data) {
  const ctx = document.getElementById("myEvolution").getContext("2d");
  const tab = await Promise.all(
    data
      .filter((el) => el.length > 0)
      .map(async (el, i) => {
        const getNameAgent = !el[0].hasOwnProperty("user_id")
          ? undefined
          : await getAgents(el[0].user_id);
        return {
          pointRadius: 0,
          lineTension: 0.3,
          borderColor: color[i],
          label: getNameAgent?.name ?? "agent",
          data: Object.values(countByDayOfWeek(el).currentWeek),
        };
      })
  );

  const myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: [
        "lundi",
        "mardi",
        "mercredi",
        "jeudi",
        "vendredi",
        "samedi",
        "dimanche",
      ],
      datasets: tab,
    },
    options: {
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          ticks: {
            stepSize: 1,
          },
        },
      },
      plugins: {
        legend: {
          display: true,
        },
      },
    },
  });
}
