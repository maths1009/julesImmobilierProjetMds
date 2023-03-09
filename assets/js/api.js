import { baseUrl } from "./constant.js";
import { countByDayOfWeek } from "./snippets.js";

/**
 * It fetches the data from the server, then counts the number of meets by day of the week
 * @returns An array of objects.
 */
export async function getMeets() {
  const response = await fetch(baseUrl + "/requests/getMeets.php");
  const data = await response.json();
  return countByDayOfWeek(data);
}

/**
 * It fetches the data from the getMeetsAgent.php file and returns the data as a JSON object
 * @returns An array of objects.
 */
export async function getMeetsAgents() {
  const response = await fetch(baseUrl + "/requests/getMeetsAgent.php");
  const data = await response.json();
  return data;
}

/**
 * It takes a value, creates a form data object, appends the value to the form data object, and then sends the form data
 * object to a PHP script
 * @param val - The value of the user id
 * @returns The data is being returned as a JSON object.
 */
export async function getAgents(val) {
  let fd = new FormData();
  fd.append("id", val);
  const response = await fetch(baseUrl + "/requests/getUser.php", {
    method: "POST",
    body: fd,
  });
  const data = await response.json();
  return data;
}
