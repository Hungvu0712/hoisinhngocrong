import api from "./api";

export const getServers = async () => {
  try {
    const response = await api.get("/servers"); // baseURL đã là /api/v1/servers/
    // Giả sử API trả về { success: true, data: [...] }
    return response.data;
  } catch (error) {
    console.error("Lỗi API:", error);
    return { success: false, message: error.message || "Lỗi API" };
  }
};
