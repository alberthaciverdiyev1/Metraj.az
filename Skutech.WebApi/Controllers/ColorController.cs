using Microsoft.AspNetCore.Mvc;
using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.WebApi.Controllers;

[Route("api/[controller]")]
[ApiController]
public class ColorController : ControllerBase
{
    private readonly IColorService _colorService;

    public ColorController(IColorService colorService)
    {
        _colorService = colorService;
    }

    [HttpGet]
    public IActionResult List()
    {
        var result = _colorService.TGetList();
        return Ok(result);
    }

    [HttpGet("{id}")]
    public IActionResult Details(int id)
    {
        var result = _colorService.TGetElementById(id);
        return Ok(result);
    }

    [HttpPost]
    public IActionResult Add(Color color)
    {
        _colorService.TInsert(color);
        return Ok();
    }

    [HttpPut]
    public IActionResult Update(Color color)
    {
        _colorService.TUpdate(color);
        return Ok();
    }

    [HttpDelete("{id}")]
    public IActionResult Delete(int id)
    {
        var value = _colorService.TGetElementById(id);
        if (value != null) _colorService.TDelete(value);
        return Ok();
    }
}