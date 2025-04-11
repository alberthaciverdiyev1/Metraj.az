using Microsoft.AspNetCore.Mvc;
using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.WebApi.Controllers;

[Route("api/[controller]")]
[ApiController]
public class PriceController : ControllerBase
{
    private readonly IPriceService _priceService;

    public PriceController(IPriceService priceService)
    {
        _priceService = priceService;
    }

    [HttpGet]
    public IActionResult List()
    {
        var result = _priceService.TGetList();
        return Ok(result);
    }

    [HttpGet("{id}")]
    public IActionResult Details(int id)
    {
        var result = _priceService.TGetElementById(id);
        return Ok(result);
    }

    [HttpPost]
    public IActionResult Add(Price price)
    {
        _priceService.TInsert(price);
        return Ok();
    }

    [HttpPut]
    public IActionResult Update(Price price)
    {
        _priceService.TUpdate(price);
        return Ok();
    }

    [HttpDelete("{id}")]
    public IActionResult Delete(int id)
    {
        var value = _priceService.TGetElementById(id);
        if (value != null) _priceService.TDelete(value);
        return Ok();
    }
}