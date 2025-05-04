function renderMembers(members, containerId, membersPerPage = members.length, currentPage = 1) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
  
    const start = (currentPage - 1) * membersPerPage;
    const end = start + membersPerPage;
    const paginated = members.slice(start, end);
  
    paginated.forEach(member => {
      const div = document.createElement("div");
      div.className = "member";
      div.innerHTML = `
        <img src="${member.img}" alt="${member.name}">
        <p><strong>${member.name}</strong><br>${member.role}</p>
      `;
      container.appendChild(div);
    });
  }
  
  function renderPagination(members, membersPerPage, paginationId, renderCallback) {
    const totalPages = Math.ceil(members.length / membersPerPage);
    const pagination = document.getElementById(paginationId);
    pagination.innerHTML = "";
  
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.innerText = `Trang ${i}`;
      btn.className = i === 1 ? "active" : "";
      btn.onclick = () => {
        renderCallback(i);
        Array.from(pagination.children).forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
      };
      pagination.appendChild(btn);
    }
  }
  
  function animateStats() {
    const stats = document.querySelectorAll(".stat-number");
    stats.forEach(stat => {
      const target = +stat.getAttribute("data-target");
      const speed = 100; // smaller = faster
      const increment = Math.ceil(target / speed);
  
      let count = 0;
      const update = () => {
        count += increment;
        if (count > target) count = target;
        stat.innerText = count;
        if (count < target) requestAnimationFrame(update);
      };
      update();
    });
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".stat-number")) {
      animateStats();
    }
  });
  