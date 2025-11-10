import { useEffect, useState } from "react";
import { getServers } from "../../services/serverService";
import Header from "../../components/Header/Header";

export default function ServerList() {
  const [servers, setServers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchServers = async () => {
      try {
        const res = await getServers();
        if (res.success) {
          setServers(res.data);
        } else {
          setError(res.message || "Không thể tải danh sách máy chủ");
        }
      } catch (err) {
        setError("Lỗi kết nối tới API");
      } finally {
        setLoading(false);
      }
    };
    fetchServers();
  }, []);

  if (loading) return <div className="p-4 text-gray-500">Đang tải...</div>;
  if (error) return <div className="p-4 text-red-500">{error}</div>;

  return (
    <>
      <Header></Header>
      <div className="p-6">
        <div className="flex justify-between items-center mb-4">
          <h1 className="text-xl font-semibold">Danh sách máy chủ</h1>
          <button className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Thêm máy chủ
          </button>
        </div>

        {servers.length === 0 ? (
          <div className="text-gray-500">Chưa có máy chủ nào.</div>
        ) : (
          <table className="w-full border border-gray-200 text-left text-sm">
            <thead className="bg-gray-100">
              <tr>
                <th className="border px-3 py-2">ID</th>
                <th className="border px-3 py-2">Tên</th>
                <th className="border px-3 py-2">Slug</th>
                <th className="border px-3 py-2">Mô tả</th>
                <th className="border px-3 py-2 text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              {servers.map((item) => (
                <tr key={item.id} className="hover:bg-gray-50">
                  <td className="border px-3 py-2">{item.id}</td>
                  <td className="border px-3 py-2">{item.name}</td>
                  <td className="border px-3 py-2">{item.slug}</td>
                  <td className="border px-3 py-2">{item.description}</td>
                  <td className="border px-3 py-2 text-center">
                    <button className="text-blue-600 hover:underline mr-2">Sửa</button>
                    <button className="text-red-600 hover:underline">Xóa</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </>

  );
}
