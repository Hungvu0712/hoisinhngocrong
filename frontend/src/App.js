import './App.css';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import ServerList from "./admin/pages/servers/ServerList";
import Dashboard from './admin/pages/Dashboard';
import PlanetList from './admin/pages/planets/PlanetList';
function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/admin" element={<Dashboard />} />
          <Route path="admin/dashboard" element={<Dashboard />} />
          <Route path="/admin/servers" element={<ServerList />} />
          <Route path="/admin/planets" element={<PlanetList />} />
        </Routes>
      </BrowserRouter>
    </>
  );
}

export default App;
