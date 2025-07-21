

document.addEventListener('DOMContentLoaded', function () {
  var trustedSwiper = new Swiper("#devicesId", {
    slidesPerView: 'auto',
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      576: {
        slidesPerView: 2,
        autoplay: {
          delay: 3000,
        },
      },
      768: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      992: {
        slidesPerView: 4,
        autoplay: {
          delay: 3000,
        },
      },
      1200: {
        slidesPerView: 5,
        autoplay: {
          delay: 3000,
        },
      }
    }
  });
});
document.addEventListener('DOMContentLoaded', function () {
  var trustedSwiper = new Swiper("#notableId", {
    slidesPerView: 'auto',
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      375: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      576: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      768: {
        slidesPerView: 4,
        autoplay: {
          delay: 3000,
        },
      },
      992: {
        slidesPerView: 5,
        autoplay: {
          delay: 3000,
        },
      },
      1200: {
        slidesPerView: 6,
        autoplay: {
          delay: 3000,
        },
      }
    }
  });
});
document.addEventListener('DOMContentLoaded', function () {
  var trustedSwiper = new Swiper("#trustedSwiper", {
    slidesPerView: 'auto',
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      375: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      576: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      768: {
        slidesPerView: 4,
        autoplay: {
          delay: 3000,
        },
      },
      992: {
        slidesPerView: 5,
        autoplay: {
          delay: 3000,
        },
      },
      1200: {
        slidesPerView: 6,
        autoplay: {
          delay: 3000,
        },
      }
    }
  });
});
document.addEventListener('DOMContentLoaded', function () {
  var trustedSwiper = new Swiper("#ourTeam", {
    slidesPerView: 'auto',
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      576: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      768: {
        slidesPerView: 2,
        autoplay: {
          delay: 3000,
        },
      },
      992: {
        slidesPerView: 3,
        autoplay: {
          delay: 3000,
        },
      },
      1200: {
        slidesPerView: 4,
        autoplay: {
          delay: 3000,
        },
      }
    }
  });
});






function redirectFeatures() {
  const element = document.getElementById("feature-content");
  if (element) {
    element.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}


$(window).on('scroll', function () {
  if ($(window).scrollTop()) {
    $('.navbar').addClass('bg-color-scroll');
  } else {
    $('.navbar').removeClass('bg-color-scroll');
  }
})



$(window).scroll(function () {
  if ($(this).scrollTop() > 50) {
    $('.scrolltop:hidden').stop(true, true).fadeIn();
  } else {
    $('.scrolltop').stop(true, true).fadeOut();
  }
});

$(document).ready(function () {
  $(".scrolltop").click(function () {
    $("html,body").animate({ scrollTop: 0 }, 1000);
    return false;
  });
});




$(document).ready(function () {

  var counters = $(".counter_value");
  var countersQuantity = counters.length;
  var counter = [];
  let newArray = [];

  // let minumuValue = Math.min(...counters);  
  // console.log("minumuValue: " + counters);


  for (i = 0; i < countersQuantity; i++) {
    counter[i] = parseInt(counters[i].innerHTML);
    newArray.push(counter[i]);

  }

  let minimunValue = Math.min(...newArray);

  if (minimunValue > 20) {
    minimunValue = 20;
  }

  var count = function (start, value, id) {
    var localStart = value - start;

    setInterval(function () {
      if (localStart < value) {
        localStart++;
        counters[id].innerHTML = localStart + "+";
      }
    }, 80);
  }

  for (j = 0; j < countersQuantity; j++) {
    count(minimunValue, counter[j], j);
  }
});


function handleChange(element) {
  const selectedOption = element.options[element.selectedIndex];
  if (selectedOption.index === 0) {
    element.classList.remove("text-black");
    element.classList.add("text-[#BABABA]");
  } else {
    element.classList.remove("text-[#BABABA]");
    element.classList.add("text-black");
  }

  // Change the color of options
  const options = element.getElementsByTagName("option");

  for (let i = 0; i < options.length; i++) {
    if (i === element.selectedIndex) {
      options[i].classList.remove("text-black");
      options[i].classList.add("text-[#BABABA]");
    } else {
      options[i].classList.remove("text-[#BABABA]");
      options[i].classList.add("text-black");
    }
  }

}


//mobile responsive sidebar start
let hamber = true;
function openDrawer() {
  if (hamber) {
    $("#hideShowHeader").css("display", "block").animate({
      right: "0px"
    }, 300);
  } else {
    closeDrawer();
  }
  hamber = !hamber;
}

function closeDrawer() {
  console.log("Close Drawer");
  $("#hideShowHeader").animate({
    right: "-300px"
  }, 300, function () {
    $(this).css("display", "none");
  });
}




