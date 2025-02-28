export const formatDate = (date: string) => {
  const formattedDate = new Date(date + "T00:00:00"); 
  return formattedDate.toLocaleDateString();
};
