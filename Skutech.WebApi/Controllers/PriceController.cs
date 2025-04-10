using Microsoft.AspNetCore.Mvc;

namespace Skutech.WebApi.Controllers;

    [Route("api/[controller]")]
    [ApiController]
public class PriceController : ControllerBase
{
 
        [HttpGet]
        public IActionResult List()
        {
            return Ok();
        }

        [HttpGet("{id}")]
        public IActionResult Details(int id)
        {
            return Ok();
        }

        [HttpPost]
        public IActionResult Add()
        {
            return Ok();
        }

        [HttpPut]
        public IActionResult Update()
        {
            return Ok();
        }

        [HttpDelete("{id}")]
        public IActionResult Delete(int id)
        {
            return Ok();
        }
}