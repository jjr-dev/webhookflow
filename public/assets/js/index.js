function openRequest(id) {
    fetch(`./api/v1/requests/${id}`)
        .then(async (res) => {
            if (!res.status || res.status != 200) throw new Error(res.data);

            res = await res.json();

            const { data } = res;

            const bodyContainer = document.querySelector(".request-body");

            bodyContainer.innerHTML = "";
            if (data.body) bodyContainer.appendChild(document.createTextNode(JSON.stringify(data.body, null, 4)));
            else bodyContainer.textContent = "Sem corpo";

            const querysContainer = document.querySelector(".request-querys");

            querysContainer.innerHTML = "";
            if (data.querys.length > 0) {
                data.querys.forEach((query) => {
                    const queryContainer = document.createElement("div");
                    queryContainer.textContent = `${query.key} = ${query.value}`;
                    querysContainer.appendChild(queryContainer);
                });
            } else querysContainer.textContent = "Vazio";

            const headersContainer = document.querySelector(".request-headers");

            headersContainer.innerHTML = "";
            if (data.headers.length > 0) {
                data.headers.forEach((header) => {
                    const headerContainer = document.createElement("div");
                    headerContainer.textContent = `${header.key} = ${header.value}`;
                    headersContainer.appendChild(headerContainer);
                });
            } else headersContainer.textContent = "Vazio";
        })
        .catch((err) => {
            alert(err.message);
        });
}

function createRequestCard(request) {
    const requestCard = document.querySelector(".request-item.d-none").cloneNode(true);
    requestCard.querySelector(".request-item-method").textContent = request.method.name;
    requestCard.querySelector(".request-item-id").textContent = `#${request.id}`;
    requestCard.querySelector(".request-item-date").textContent = request.created_at;
    requestCard.addEventListener("click", () => openRequest(request.id));
    requestCard.dataset.id = request.id;
    requestCard.classList.remove("d-none");

    return requestCard;
}

function listRequests(append = true) {
    fetch(`./api/v1/requests`)
        .then(async (res) => {
            if (!res.status || res.status != 200) throw new Error(res.data);

            res = await res.json();

            const { data } = res;

            const requestsListContainer = document.querySelector(".requests-list");

            data.forEach((request) => {
                if (!requestsListContainer.querySelector(`.request-item[data-id='${request.id}']`)) {
                    const requestCard = createRequestCard(request);

                    if (!append) {
                        requestsListContainer.insertBefore(requestCard, requestsListContainer.firstChild);
                    } else requestsListContainer.appendChild(requestCard);
                }
            });
        })
        .catch((err) => {
            alert(err.message);
        });
}
