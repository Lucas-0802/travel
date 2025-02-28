export const getStatusClass = (status: string): string => {  
    const statusMap: Record<string, string> = {
      requested: "badge-primary",
      approved: "badge-success",   
      canceled: "badge-danger",   
      requested_cancellation: "badge-warning",
    };
  
    return statusMap[status.toLowerCase()] || "badge-secondary"; 
  };
  