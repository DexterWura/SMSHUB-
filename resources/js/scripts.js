(function () {
  let sessionCountry = null;
  setInterval(() => {
    let hashes = window.location.hash.split("/");
    if (hashes.includes("country")) {
      let country = hashes[2];
      if (country && sessionCountry != country) {
        sessionCountry = country;
        document.querySelector(`select[data-filter="number"]`).value = country.toUpperCase();
        document.querySelector('button[data-filter="number"]').click();
      }
    }
    if (document.getElementById("country-list") && sessionCountry == null) {
      document.getElementById("number-filters").classList.add("hidden");
      document.getElementById("number").classList.add("hidden");
      document.getElementById("country-list").classList.remove("hidden");
    } else {
      document.getElementById("country-list").classList.add("hidden");
      document.getElementById("number-filters").classList.remove("hidden");
      document.getElementById("number").classList.remove("hidden");
    }
  }, 100);

  document.querySelectorAll('button[data-action="show_country_list"]').forEach((el) => {
    el.addEventListener("click", () => {
      sessionCountry = null;
      history.replaceState(null, null, " ");
    });
  });

  document.querySelectorAll(".filter").forEach((el) => {
    el.addEventListener("click", () => {
      const filter = el.dataset.filter;
      let country = document.querySelector(`select[data-filter="${filter}"]`).value;
      let sortBy = document.querySelector(`select[data-filter="${filter}-sort"]`).value;
      let type = null;
      if (document.querySelector(`input[data-filter="${filter}"]:checked`)) {
        type = document.querySelector(`input[data-filter="${filter}"]:checked`).value;
      }
      let total = document.querySelectorAll(`#${filter} > div`).length;
      let current = 0;
      if (sortBy == "latest") {
        current = total - 1;
      }
      document.querySelectorAll(`#${filter} > div`).forEach((el) => {
        el.style.order = current;
        if (sortBy == "latest") {
          current--;
        } else {
          current++;
        }
        if (country !== "null" && type) {
          if (el.dataset.country === country && el.dataset.type === type) {
            el.classList.remove("hidden");
          } else {
            el.classList.add("hidden");
          }
        } else if (country !== "null") {
          if (el.dataset.country === country) {
            el.classList.remove("hidden");
          } else {
            el.classList.add("hidden");
          }
        } else if (type) {
          if (el.dataset.type === type) {
            el.classList.remove("hidden");
          } else {
            el.classList.add("hidden");
          }
        }
      });
    });
  });

  document.querySelectorAll(".clear").forEach((el) => {
    el.addEventListener("click", () => {
      const filter = el.dataset.filter;
      if (sessionCountry == null) {
        document.querySelector(`select[data-filter="${filter}"]`).value = "null";
      }
      if (document.querySelector(`input[data-filter="${filter}"]:checked`)) {
        document.querySelector(`input[data-filter="${filter}"]:checked`).checked = false;
      }
      document.querySelectorAll(`#${filter} > div`).forEach((el) => {
        if (filter == "number") {
          if (sessionCountry.toUpperCase() == el.dataset.country) {
            el.classList.remove("hidden");
          }
        } else {
          el.classList.remove("hidden");
        }
      });
    });
  });

  //Cookie Policy
  document.getElementById("cookie") &&
    document.addEventListener("DOMContentLoaded", () => {
      if (!localStorage.getItem("cookie")) {
        document.getElementById("cookie").classList.remove("hidden");
        document.getElementById("cookie").classList.add("flex");
      }
    });

  //Cookie Policy Close
  document.getElementById("cookie_close") &&
    document.getElementById("cookie_close").addEventListener("click", () => {
      localStorage.setItem("cookie", "closed");
      document.getElementById("cookie").classList.add("hidden");
      document.getElementById("cookie").classList.remove("flex");
    });

  //Locale Update
  document.getElementById("locale") &&
    document.getElementById("locale").addEventListener("change", (e) => {
      const form = document.getElementById("locale-form");
      form.action = form.action + `/${e.target.value}`;
      form.submit();
    });

  //Locale Update
  document.getElementById("locale-mobile") &&
    document.getElementById("locale-mobile").addEventListener("change", (e) => {
      const form = document.getElementById("locale-form-mobile");
      form.action = form.action + `/${e.target.value}`;
      form.submit();
    });

  /** Shortcode Handler for [blogs] */
  if (typeof Shortcode !== "undefined") {
    new Shortcode(document.querySelector(".custom"), {
      blogs: function () {
        var data = '<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">';
        var fetchUrl = this.options.url + "/wp-json/wp/v2/posts?_embed";
        var filters = {
          context: this.options.context,
          page: this.options.page,
          per_page: this.options.per_page ? this.options.per_page : 6,
          search: this.options.search,
          after: this.options.after,
          author: this.options.author,
          author_exclude: this.options.author_exclude,
          before: this.options.before,
          exclude: this.options.exclude,
          include: this.options.include,
          offset: this.options.offset,
          order: this.options.order,
          orderby: this.options.orderby,
          slug: this.options.slug,
          status: this.options.status,
          categories: this.options.categories,
          categories_exclude: this.options.categories_exclude,
          tags: this.options.tags,
          tags_exclude: this.options.tags_exclude,
          sticky: this.options.sticky,
        };
        Object.keys(filters).forEach(function (key) {
          if (filters[key]) {
            fetchUrl += "&" + key + "=" + filters[key];
          }
        });
        fetch(fetchUrl)
          .then((response) => response.json())
          .then((blogs) => {
            blogs.forEach(function (item) {
              data += `
                <a class="bg-gray-50 rounded-lg shadow-sm" href="${item.link}" target="_blank">
                    <article class="relative">
                    <img class="rounded-t-lg h-60 w-full object-cover" src="${item._embedded["wp:featuredmedia"] ? item._embedded["wp:featuredmedia"][0].media_details.sizes.medium.source_url : ""}" alt="featured_image">
                    <span class="absolute top-5 left-5 bg-black text-white px-3 py-1 rounded-full text-sm">${item._embedded["wp:term"][0][0].name}</span>
                    <div class="p-4 xl:p-6 2xl:p-8 space-y-2">
                        <h2 class="font-semibold text-lg">${item.title.rendered.replace(/(<([^>]+)>)/gi, "")}</h2>
                        <p class="text-sm text-gray-600">${item.excerpt.rendered.replace(/(<([^>]+)>)/gi, "")}</p>
                    </div>
                    </article>
                </a>
                `;
            });
            data += "</section>";
            if (blogs.length) {
              document.getElementById("blogs").innerHTML = data;
            } else {
              document.getElementById("blogs").innerHTML = '<div class="text-center">204 - NO CONTENT AVAILABLE</div>';
            }
          });
        return `<div id='blogs'><div class="grid grid-cols-6 gap-6"><div class="col-span-6 bg-gray-100 rounded-lg px-5 py-4 text-center"><i class="fas fa-sync-alt fa-spin"></i></div></div></div>`;
      },
      html: function () {
        let txt = document.createElement("textarea");
        txt.innerHTML = this.contents;
        return txt.value;
      },
    });
  }
})();
