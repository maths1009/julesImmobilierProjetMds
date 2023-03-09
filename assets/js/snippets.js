/**
 * It takes an array of objects, each object having a start_date property, and returns an object with two properties:
 * currentWeek and previousWeek. Each of these properties is an object with seven properties, one for each day of the week,
 * and each of these properties is a number representing the number of items in the input array that have a start_date
 * property that falls on that day of the week
 * @param data - an array of objects, each object representing a single event
 * @returns An object with two properties: currentWeek and previousWeek.
 */
export function countByDayOfWeek(data) {
  const now = new Date();
  const currentWeekStart = new Date(
    now.getFullYear(),
    now.getMonth(),
    now.getDate() - now.getDay()
  );
  const previousWeekStart = new Date(
    now.getFullYear(),
    now.getMonth(),
    now.getDate() - now.getDay() - 7
  );
  const currentWeek = {
    lundi: 0,
    mardi: 0,
    mercredi: 0,
    jeudi: 0,
    vendredi: 0,
    samedi: 0,
    dimanche: 0,
  };
  const previousWeek = {
    lundi: 0,
    mardi: 0,
    mercredi: 0,
    jeudi: 0,
    vendredi: 0,
    samedi: 0,
    dimanche: 0,
  };

  data.forEach((item) => {
    const startDate = new Date(item.start_date);
    if (startDate >= currentWeekStart && startDate < now) {
      const dayOfWeek = startDate
        .toLocaleString("fr-FR", { weekday: "long" })
        .toLowerCase();
      currentWeek[dayOfWeek]++;
    } else if (startDate >= previousWeekStart && startDate < currentWeekStart) {
      const dayOfWeek = startDate
        .toLocaleString("fr-FR", { weekday: "long" })
        .toLowerCase();
      previousWeek[dayOfWeek]++;
    }
  });

  return { currentWeek, previousWeek };
}
