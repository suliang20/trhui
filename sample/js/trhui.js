//  提交清算通
function sendData (action, data) {
  var name,
    form = document.createElement('form'),
    node = document.createElement('input')

  form.action = action
  form.method = 'post'

  for (name in data) {
    node.name = name
    node.value = data[name].toString()
    form.appendChild(node.cloneNode())
  }

  // 表单元素需要添加到主文档中.
  form.style.display = 'none'
  document.body.appendChild(form)

  form.submit()

  // 表单提交后,就可以删除这个表单,不影响下次的数据发送.
  document.body.removeChild(form)
}

//  提交后台
$(document).ready(function () {
  $('#trhuiForm').submit(function (e) {
    e.preventDefault();
  })

  $('#submitPay').click(function () {
    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: $('#payForm').serialize(), // 要提交的表单,必须使用name属性
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
//                        console.log(data.data.businessData);
          sendData(data.data.businessUrl, data.data.businessData)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  })

  $('#trhuiSubmit').click(function () {
    console.log($('#trhuiForm').serialize());

    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: $('#trhuiForm').serialize(), // 要提交的表单,必须使用name属性
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
//                        console.log(data.data.businessData);
          sendData(data.data.businessUrl, data.data.businessData)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  })

  $('#trhuiAjaxSubmit').click(function () {
    console.log($('#trhuiForm').serialize());
    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: $('#trhuiForm').serialize(), // 要提交的表单,必须使用name属性
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
          alert(data.msg)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  })
})

