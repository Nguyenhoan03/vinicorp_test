<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trang quản lý</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">
  @include('components.Header_admin')

  <div class="flex">
    @include('components.Sidebar_admin')

    <main class="flex-1 p-6 space-y-6">
      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-pink-500 text-white p-4 rounded-lg shadow">
          <h3 class="text-sm">NEW TASKS</h3>
          <p class="text-2xl font-bold">125</p>
        </div>
        <div class="bg-cyan-500 text-white p-4 rounded-lg shadow">
          <h3 class="text-sm">NEW TICKETS</h3>
          <p class="text-2xl font-bold">257</p>
        </div>
        <div class="bg-green-500 text-white p-4 rounded-lg shadow">
          <h3 class="text-sm">NEW COMMENTS</h3>
          <p class="text-2xl font-bold">243</p>
        </div>
        <div class="bg-orange-400 text-white p-4 rounded-lg shadow">
          <h3 class="text-sm">NEW VISITORS</h3>
          <p class="text-2xl font-bold">1225</p>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-bold">CPU USAGE (%)</h2>
          <span class="text-sm text-gray-600">REAL TIME</span>
        </div>
        <canvas id="cpuChart" height="100"></canvas>
      </div>

      <!-- Bottom Stats Section -->
      <div class="grid md:grid-cols-3 gap-4">
        <!-- Today Stats -->
        <div class="bg-pink-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-md font-semibold mb-2">TODAY</h3>
          <p class="text-lg">3,200 đơn</p>
          <p class="text-md mt-2">YESTERDAY: 3,872</p>
          <p class="text-md">LAST WEEK: 5,123</p>
        </div>

        <!-- Trends -->
        <div class="bg-cyan-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-md font-semibold mb-2">LATEST SOCIAL TRENDS</h3>
          <ul class="list-disc ml-5 space-y-1">
            <li>#socialnetwork</li>
            <li>#materialdesign</li>
            <li>#admin</li>
            <li>#bootstraptemplate</li>
            <li>#modernadmin</li>
          </ul>
        </div>

        <!-- Tickets Stats -->
        <div class="bg-teal-700 text-white p-6 rounded-lg shadow">
          <h3 class="text-md font-semibold mb-2">ANSWERED TICKETS</h3>
          <ul class="space-y-1">
            <li>Today: 12 tickets</li>
            <li>Yesterday: 15</li>
            <li>Last Week: 142</li>
            <li>Last Month: 489</li>
            <li>All Time: 6,982</li>
          </ul>
        </div>
      </div>
    </main>
  </div>

  <script>
    const ctx = document.getElementById('cpuChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: Array.from({length: 100}, (_, i) => i + 1),
        datasets: [{
          label: 'CPU Usage',
          data: Array.from({length: 100}, () => Math.floor(Math.random() * 60) + 20),
          borderColor: '#0ea5e9',
          backgroundColor: 'rgba(14, 165, 233, 0.3)',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { min: 0, max: 100 }
        }
      }
    });
  </script>
</body>
</html>
